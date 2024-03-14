<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_upload extends MY_Controller
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

    function all_upload()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        $this->load->model('model_assets');
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
        $this->view_assets();

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function view_assets(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $data['page_content'] = 'upload/upload_form_view';                      
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);        
    }

    public function file_upload(){

      if(!is_dir('./assets/uploads/zip/'.date('Ym').'/'))
      {
          @mkdir('./assets/uploads/zip/'.date('Ym').'/',0777);
      }

      //konfigurasi upload zip
        $config['upload_path'] = './assets/uploads/zip/'.date('Ym').'';
        $config['allowed_types'] = 'zip' || 'ZIP';
        $config['max_size'] = '';
        $config['overwrite'] = 'TRUE';
        
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload())
        {
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('upload/upload_form_view', $error);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $zip = new ZipArchive;
            $file = $data['upload_data']['full_path'];
            chmod($file,0777);

            //mengambil no cabang dari orig_name => 96
            $upload_data = $this->upload->data();
            $nocab = substr($upload_data['orig_name'],2,2);
            $year  = $this->input->post('year');
            $month = substr($upload_data['orig_name'],4,2);
            /*
            echo "nocab : ".$nocab.'<br />';
            echo 'tahun : '.$year.'<br />';
            echo 'bulan : '.$month.'<br />';
            */


            //proses ekstraksi zip
            $openZip = $zip->open($file);

            if ($openZip === TRUE) 
            {               
                if ($zip->setPassword("DELTOMED"))
                {                 
                    if (!$zip->extractTo('./assets/uploads/unzip/'.$nocab.'')){

                        echo "Extraction failed (wrong password?)";
                    }else{

                        $pesan_extract = "Extraction Berhasil";
                    }
                       
                }

            $zip->close();
            }
            else
            {
                die("Failed opening archive: ". @$zip->getStatusString() . " (code: ". $zip_status .")");
            }

            //proses ekstraksi zip
            /*
            if ($zip->open($file) === TRUE) {
                $zip->extractTo('./assets/uploads/unzip/'.$nocab.'');
                $zip->close();

                //echo 'Extraction Berhasil ...<br><hr><br>';
                  $pesan_extract = "Extraction Berhasil";
                } 

                else 
                {
                  echo 'Extraction Failed. Please check your connection and Try Again..';
                  //redirect('all_upload','refresh');
                }
              */

                $data['page_content'] = 'upload/upload_diproses';                      
                $data['menu']=$this->db->query($this->querymenu);
                $data['nocab']=$nocab;
                $data['tahun'] = $year;
                $data['bulan'] = $month;
                $data['pesan'] = $pesan_extract;
                $data['filenamezip'] = $upload_data['orig_name'];
                
                $upl['userid']=$this->session->userdata('id');
                $upl['lastupload']=date('Y-m-d H:i:s');
                $upl['filename']= $upload_data['orig_name'];
                $upl['tahun']=$year;
                $upl['status']='0';
                $this->db->insert('mpm.upload',$upl);

                $this->template($data['page_content'],$data);
        }

    }

    private function console($filename,$year='')
        {
            set_time_limit(0);
            ini_set('mysql.connect_timeout', 3000);
            ini_set('default_socket_timeout', 3000);
            $logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            set_time_limit(0);

            $ddl1 = array('bd','bg','bp','eh','ei','fi','fh','gh','gi','ko','kr','ld','lh','mh',
                          'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','sk','st','qh','qi','td','tg','th','ti','tr','tu','vh','vi'
                        );
            $ddl2 = array('tblang','tbkota');
            $ddl3 = array('tabsupp','tabsales','tabsalur','tabtype','norayon','tabfaktr','tabgrupp');
            $month = substr($filename,4,2);
            $nocab = substr($filename,2,2);
            if($year=='')
            {
                $year  = substr($this->input->post('year'),2,2);
            }
            $this->db->trans_begin();
            $msg=array();
            
            //$load="LOAD DATA INFILE '".realpath('.')."/assets/uploads/unzip/".$nocab."/";

            $load="LOAD DATA INFILE 'C:/xampp/htdocs/cisk/assets/uploads/unzip/".$nocab."/";
            
            foreach($ddl1 as $ddl)
            {
                //echo "dd1 : ".$ddl."<br>"; 
                $fields = $this->db->field_data('data20'.$year.'.'.$ddl);
                //echo "fields : ".$fields."<br>"; 
                $name='(';
                $set=',';
                $i=1;
                foreach ($fields as $field)
                {

                    if($field->type=='date')
                    {
                        //echo "ini muncul jika type = date<br>";
                        $name.='@date'.$i.',';
                        $set .=$field->name.'='.'str_to_date(@date'.$i.',"%d-%m-%Y"),';
                        $i++;
                    }
                    else
                    {
                        //echo "ini muncul jika type = date else<br>";
                        $name.=$field->name.", ";
                    }
                }
                $name = substr($name, 0, -2);
                $name.=')';
                $set  = substr($set, 0, -1);

                //$file=realpath('.').'/assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.$year.$month.'.TXT';

                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.$year.$month.'.TXT';
                //echo "file : ".$file."<br>";
                //echo "file ddl1 : ".$file."<br>";    
                if(file_exists($file))
                {
                    if($ddl=='st')
                    {
                        //echo "ini muncul jika ddl = st<br>";
                        //contoh : where nocab='.'".$nocab."'.'';
                        $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.'"'.$nocab.'"'." and bulan =".$year.$month;
                        $this->db->query($sql_del);
                       
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$year.$month."' ".$set;
                        $this->db->query($sql);
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';
                    }
                    else
                    {
                        //echo "ini muncul jika ddl = st else<br>";
                        $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.'"'.$nocab.'"'." and bulan =".$month;
                        $this->db->query($sql_del);
                        
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        //echo "<br>sql : ".$sql."<br>";
                        $this->db->query($sql);
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';
                    //$this->delete_files($file);
                    }
                }
                else {

                        //echo "ini muncul jika else<br>";
                        //echo "".strtoupper($ddl)."<br>";      

                    $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' not founds<BR />';
                }
            }
            foreach($ddl2 as $ddl)
            {
                $fields = $this->db->field_data('data20'.$year.'.'.$ddl);

                //echo "ddl : ".$ddl."<br>";

                $name='(';
                $set=',';
                $i=1;
                foreach ($fields as $field)
                {

                    if($field->type=='date')
                    {
                        $name.='@date'.$i.',';
                        $set .=$field->name.'='.'str_to_date(@date'.$i.',"%d-%m-%Y"),';
                        $i++;
                    }
                    else
                    {
                        $name.=$field->name.", ";
                    }
                }
                $name = substr($name, 0, -2);
                $name.=')';
                $set  = substr($set, 0, -1);
                //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/'.$ddl.$nocab.'.TXT';
                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.'.TXT';
                //echo "file ddl2 : ".$file."<br>";
                if(is_file($file))
                {
                    //nocab='.'"'.$nocab.'"'."
                    $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.'"'.$nocab.'"';
                    $this->db->query($sql_del);
                    $sql = $load.strtoupper($ddl).$nocab.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."' ".$set;
                    $this->db->query($sql);

                    //echo "sql : ".$sql."<br>";

                    $msg[]= $ddl.$nocab.'.TXT'.' found and uploaded<br />';
                    //$this->delete_files($file);
                }
                else {
                    $msg[]= $ddl.$nocab.'.TXT'.' not found<BR />';
                }
            }
            $sql_del='delete from data20'.$year.'.tblang where nocab='.$nocab.' and kode_lang =""';
            $this->db->query($sql_del);
            foreach($ddl3 as $ddl)
            {
                $fields = $this->db->field_data('data20'.$year.'.'.$ddl);

                $name='(';
                $set=',';
                $i=1;
                foreach ($fields as $field)
                {

                    if($field->type=='date')
                    {
                        $name.='@date'.$i.',';
                        $set .=$field->name.'='.'str_to_date(@date'.$i.',"%d-%m-%Y"),';
                        $i++;
                    }
                    else
                    {
                        $name.=$field->name.", ";
                    }
                }
                $name = substr($name, 0, -2);
                $name.=')';
                $set  = substr($set, 0, -1);
                //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/'.$ddl.'.TXT';
                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).'.TXT';
                if(is_file($file))
                {
                    $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.$nocab;
                    $this->db->query($sql_del);
                    $sql = $load.strtoupper($ddl).".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."' ".$set;
                    $this->db->query($sql);
                    $msg[]= $ddl.'.TXT'.' found and uploaded<br />';
                    //$this->delete_files($file);
                }
                else {
                    $msg[]= $ddl.'.TXT'.' not found<BR />';
                }
            }

            //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/tbprod.TXT';
            $file='./assets/uploads/unzip/'.$nocab.'/TBPROD.TXT';
            if(is_file($file))
            {
                $sql_del='delete from data20'.$year.'.tabprod where nocab='.$nocab;
                $this->db->query($sql_del);
                $sql = $load."TBPROD.TXT' INTO TABLE data20".$year.".tabprod FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\r\n' SET NOCAB='".$nocab."'";
                $this->db->query($sql);
                $msg[]= 'TBPROD.TXT'.' found and uploaded<br />';
            }
            else {
                $msg[]= 'tbprod.TXT'.' not found<BR />';
            }
            //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/tbrayo~1.TXT';
            $file='./assets/uploads/unzip/'.$nocab.'/TBRAYO~1.TXT';
            if(is_file($file))
            {
               $sql_del='delete from data20'.$year.'.tbrayon where nocab='.$nocab;
               $this->db->query($sql_del);
               $sql = $load."TBRAYO~1.TXT' INTO TABLE data20".$year.".tbrayon FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\r\n' SET NOCAB='".$nocab."'";
               $this->db->query($sql);
               $msg[]= 'tbrayo~1.TXT'.' found and uploaded<br />';
            }
            else {
               $msg[]= 'tbrayo~1.TXT'.' not found<BR />';
            }

            $sql_del='delete from data20'.$year.'.fi where nodokjdi="XXXXXX"';
            //$sql_del='delete from data'.$year.'.fh where nodokjdi="XXXXXX"';
            $this->db->query($sql_del);
            $sql_upd="update data20".$year.".tblang set custid=concat(nocab,kode_lang),compid=concat(kode_comp,kode_lang) where nocab=".'".$nocab."';
            $this->db->query($sql_upd);
            $sql_pil_fi="update data20".$year.".fi set kodeprod='020002' where kodeprod='020002 P'"; 
            $this->db->query($sql_pil_fi);
            $sql_pil_ri="update data20".$year.".ri set kodeprod='020002' where kodeprod='020002 P'"; 
            
            $this->db->query($sql_pil_ri);
            $sql_madu_fi="update data20".$year.".fi set kodeprod='060009' where kodeprod='06009'";
            $this->db->query($sql_madu_fi);
            $sql_madu_ri="update data20".$year.".ri set kodeprod='060009' where kodeprod='06009'";
            $this->db->query($sql_madu_ri);
            $delete00fi = "delete from data20".$year.".fi where left(kodeprod,2)='00'";
            $this->db->query($delete00fi);
            $delete00ri = "delete from data20".$year.".ri where left(kodeprod,2)='00'";
            $this->db->query($delete00ri);
            
            $upl['userid']=$this->session->userdata('id');
            $upl['lastupload']=date('Y-m-d H:i:s');
            $upl['filename']=$filename;
            $upl['tahun']=$year;
            $upl['status']='0';
            $this->db->insert('mpm.upload',$upl);
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
            
            return $msg; 



        }
   
}
?>
