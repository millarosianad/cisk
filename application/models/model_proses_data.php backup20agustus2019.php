<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_proses_data extends CI_Model {

 
    
    public function view_upload(){

        $userid=$this->session->userdata('id');
        $this->db->where('userid = '.'"'.$userid.'"');
        $this->db->order_by("id", "desc"); 
        $hasil = $this->db->get('mpm.upload', 30);
        
        if ($hasil->num_rows() > 0) 
        {

            return $hasil->result();
        } else {
            return array();
        }
    }

    public function view_upload_all_dp(){

        $userid=$this->session->userdata('id');
        //$this->db->where('userid = '.'"'.$userid.'"');
        $this->db->order_by("id", "desc"); 
        $hasil = $this->db->get('mpm.upload', 200);
        
        if ($hasil->num_rows() > 0) 
        {

            return $hasil->result();
        } else {
            return array();
        }
    }

    public function hitung_omzet(){
        
        $userid=$this->session->userdata('id');
        //echo "userid : ".$userid."<br>";

        /* cari nilai filename,tahun */
        $this->db->order_by("id", "desc");
        $this->db->where('userid = '.'"'.$userid.'"');
        $query = $this->db->get('mpm.upload',1);
        foreach ($query->result() as $row) {
            $filename = $row->filename;
            $year = $row->tahun;
            $month = substr($row->filename, 4,2);
            $nocab = substr($row->filename, 2,2);
            
            //$year = '16';
            /*
            echo "filename : ".$filename."<br />";
            echo "tahun : ".$year."<br />";
            echo "month : ".$month."<br />";
            echo "nocab : ".$nocab."<br />";
            */
        }
        /* end cari nilai filename */

        $sql='
            select format(sum(val),2) val 
            from
            (
                select sum(tot1) val from data'.$year.'.fi where bulan='.$month.' and nocab='.'"'.$nocab.'"'.'
                union ALL
                select sum(tot1) val from data'.$year.'.ri where bulan='.$month.' and nocab='.'"'.$nocab.'"'.'
            )a';

            //print_r($sql);

        $result = $this->db->query($sql)->row_array();
        return $result['val'];
    }

    public function hitung_omzet2($data_model){
        
        $userid=$this->session->userdata('id');
        echo "userid : ".$userid."<br>";

        $id = $data_model['id'];

        /* cari nilai filename,tahun */
        $this->db->where('id = '.'"'.$id.'"');
        $query = $this->db->get('mpm.upload',1);
        foreach ($query->result() as $row) {
            $filename = $row->filename;
            $year = $row->tahun;
            $month = substr($row->filename, 4,2);
            $nocab = substr($row->filename, 2,2);
        }
        $sql='
            select format(sum(val),2) val 
            from
            (
                select sum(tot1) val from data'.$year.'.fi where bulan='.$month.' and nocab='.'"'.$nocab.'"'.'
                union ALL
                select sum(tot1) val from data'.$year.'.ri where bulan='.$month.' and nocab='.'"'.$nocab.'"'.'
            )a';

            print_r($sql);

        $result = $this->db->query($sql)->row_array();
        return $result['val'];
    }

    public function hitung_omzet3($data_model){
        
        $userid=$this->session->userdata('id');
        //echo "userid : ".$userid."<br>";

        $lastupload = $data_model['lastupload'];
        //echo "lastupload : ".$lastupload;
        /* cari nilai filename,tahun */
        $this->db->where('lastupload = '.'"'.$lastupload.'"');
        $query = $this->db->get('mpm.upload',1);
        foreach ($query->result() as $row) {
            $filename = $row->filename;
            $year = $row->tahun;
            $month = substr($row->filename, 4,2);
            $nocab = substr($row->filename, 2,2);
        }
        $sql='
            select format(sum(val),2) val 
            from
            (
                select sum(tot1) val from db_upload.fi where bulan='.$month.' and nocab='.'"'.$nocab.'"'.'
                union ALL
                select sum(tot1) val from db_upload.ri where bulan='.$month.' and nocab='.'"'.$nocab.'"'.'
            )a';

            //print_r($sql);

        $result = $this->db->query($sql)->row_array();
        return $result['val'];
    }

    public function hitung_omzet4($data_model){
        
        $userid=$this->session->userdata('id');
        //echo "userid : ".$userid."<br>";
        $id = $this->uri->segment('6');

        /* cari nilai filename,tahun */
        $this->db->where('id = '.'"'.$id.'"');
        $query = $this->db->get('mpm.upload',1);
        foreach ($query->result() as $row) {
            $filename = $row->filename;
            $year = $row->tahun;
            $month = substr($row->filename, 4,2);
            $nocab = substr($row->filename, 2,2);
        }
        $sql='
            select format(sum(val),2) val 
            from
            (
                select sum(tot1) val from data'.$year.'.fi where bulan='.$month.' and nocab='.'"'.$nocab.'"'.'
                union ALL
                select sum(tot1) val from data'.$year.'.ri where bulan='.$month.' and nocab='.'"'.$nocab.'"'.'
            )a';

            //print_r($sql);

        $result = $this->db->query($sql)->row_array();
        return $result['val'];
    }

    public function proses_data_omzet(){

        $userid=$this->session->userdata('id');
        $id_upload = $this->uri->segment(3);
        echo "userid : ".$userid."<br>";

        /* cari nilai filename,tahun */
        $this->db->order_by("id", "desc");
        $this->db->where('userid = '.'"'.$userid.'"');
        $query = $this->db->get('mpm.upload',1);
        foreach ($query->result() as $row) {
            $filename = $row->filename;
            $year = substr($row->tahun, 2,2);
            $month = substr($row->filename, 4,2);
            $nocab = substr($row->filename, 2,2);
            
            //$year = '16';
            
            echo "filename : ".$filename."<br />";
            echo "year : ".$year."<br />";
            echo "month : ".$month."<br />";
            echo "nocab : ".$nocab."<br />";
            
        }
        /* end cari nilai filename */

        set_time_limit(0);
        ini_set('mysql.connect_timeout', 3000);
        ini_set('default_socket_timeout', 3000);
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);

        /*
        $ddl1 = array('bd','bg','bp','eh','ei','fi','fh','gh','gi','ko','kr','ld','lh','mh',
                      'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','sk','st','qh','qi','td','tg','th','ti','tr','tu','vh','vi'
                    );
        $ddl2 = array('tblang','tbkota');
        $ddl3 = array('tabsupp','tabsales','tabsalur','tabtype','norayon','tabfaktr','tabgrupp');
        
        */

        $ddl1 = array('bd','bg','bp','eh','ei','fi','fh','gh','gi','ko','kr','ld','lh','mh',
                      'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','sk','st','qh','qi','td','tg','th','ti','tr','tu','vh','vi'
                    );
        $ddl2 = array('tblang','tbkota');
        $ddl3 = array('tabsupp','tabsales','tabsalur','tabtype','norayon','tabfaktr','tabgrupp');


        if($year=='')
        {
            $year  = substr($this->input->post('year'),2,2);
        }
        $this->db->trans_begin();
        $msg=array();
        
        $load="LOAD DATA INFILE 'C:/xampp/htdocs/cisk/assets/uploads/unzip/".$nocab."/";
            
            foreach($ddl1 as $ddl)
            {
                //echo "dd1 : ".$ddl."<br>"; 
                $fields = $this->db->field_data('data20'.$year.'.'.$ddl);
         
                //print_r($fields);
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

                $cek = file_exists($file);
                //print_r($cek);
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

                        //echo "ini muncul jika else / file nya ga exist<br>";
                        //echo "".strtoupper($ddl)."<br>";      

                    $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' not founds<BR />';

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

            //update kolom status di tabel mpm.upload
            $sql_madu_ri="update mpm.upload set status='1' where id=$id_upload";
            $proses = $this->db->query($sql_madu_ri);

            echo "proses : ".$proses;
            if ($proses == '1') {
                echo "berhasil";
                redirect('proses_data/view_upload');
            }else
            {
                echo "ada kesalahan, silahkan ulangi";
                redirect('proses_data/view_upload','refresh');
            }
            
    }

    public function proses_data_omzet_all_dp($datacontroller){

                $userid=$this->session->userdata('id');
        $id_upload = $this->uri->segment(3);
        echo "userid : ".$userid."<br>";
        echo "id_upload : ".$id_upload."<br>";

        /* cari nilai filename,tahun */
        $this->db->order_by("id", "desc");
        $this->db->where('id = '.'"'.$id_upload.'"');
        $query = $this->db->get('mpm.upload',1);
        foreach ($query->result() as $row) {
            $filename = $row->filename;
            $year = substr($row->tahun, 2,2);
            $month = substr($row->filename, 4,2);
            $nocab = substr($row->filename, 2,2);
            
            //$year = '16';
            
            echo "filename : ".$filename."<br />";
            echo "year : ".$year."<br />";
            echo "month : ".$month."<br />";
            echo "nocab : ".$nocab."<br />";
            
        }
        /* end cari nilai filename */

        set_time_limit(0);
        ini_set('mysql.connect_timeout', 3000);
        ini_set('default_socket_timeout', 3000);
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);

        /*
        $ddl1 = array('bd','bg','bp','eh','ei','fi','fh','gh','gi','ko','kr','ld','lh','mh',
                      'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','sk','st','qh','qi','td','tg','th','ti','tr','tu','vh','vi'
                    );
        $ddl2 = array('tblang','tbkota');
        $ddl3 = array('tabsupp','tabsales','tabsalur','tabtype','norayon','tabfaktr','tabgrupp');
        
        */

        $ddl1 = array('bd','bg','bp','eh','ei','fi','fh','gh','gi','ko','kr','ld','lh','mh',
                      'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','st','qh','qi','td','tg','th','ti','tr','tu','vh','vi'
                    );
        $ddl2 = array('tblang','tbkota');
        $ddl3 = array('tabsupp','tabsales','tabsalur','tabtype','norayon','tabfaktr','tabgrupp');


        if($year=='')
        {
            $year  = substr($this->input->post('year'),2,2);
        }
        $this->db->trans_begin();
        $msg=array();
        
        $load="LOAD DATA INFILE 'C:/xampp/htdocs/cisk/assets/uploads/unzip/".$nocab."/";
        
            foreach($ddl1 as $ddl)
            {
                //echo "dd1 : ".$ddl."<br>"; 
                $fields = $this->db->field_data('data20'.$year.'.'.$ddl);
         
                //print_r($fields);
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

                $cek = file_exists($file);
                //print_r($cek);
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
                        /*
                        echo "<pre>";
                        print_r($sql_del);
                        print_r($sql);
                        echo "</pre>";
                        */
                    }
                    else
                    {
                        //echo "ini muncul jika ddl = st else<br>";
                        $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.'"'.$nocab.'"'." and bulan =".$month;
                        $this->db->query($sql_del);
                        
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        $this->db->query($sql);
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';
                        /*
                        echo "<pre>";
                        print_r($sql_del);
                        print_r($sql);
                        echo "</pre>";
                        */
                    }
                }
                else {
                    $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' not founds<BR />';
                }
            }

            foreach($ddl2 as $ddl)
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
                    $msg[]= $ddl.$nocab.'.TXT'.' found and uploaded<br />';
                    /*
                    echo "<pre>";
                    print_r($sql_del);
                    print_r($sql);
                    echo "</pre>";
                    */
                }
                else {
                    $msg[]= $ddl.$nocab.'.TXT'.' not found<BR />';
                }
            }
            $sql_del='delete from data20'.$year.'.tblang where nocab='.'"'.$nocab.'"'.' and kode_lang =""';
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
                    $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.'"'.$nocab.'"';
                    $this->db->query($sql_del);
                    $sql = $load.strtoupper($ddl).".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."' ".$set;
                    $this->db->query($sql);
                    $msg[]= $ddl.'.TXT'.' found and uploaded<br />';
                    /*
                    echo "<pre>";
                    print_r($sql_del);
                    print_r($sql);
                    echo "</pre>";
                    */
                }
                else {
                    $msg[]= $ddl.'.TXT'.' not found<BR />';
                }
            }  

            //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/tbprod.TXT';
            $file='./assets/uploads/unzip/'.$nocab.'/TBPROD.TXT';
            if(is_file($file))
            {
                $sql_del='delete from data20'.$year.'.tabprod where nocab='.'"'.$nocab.'"';
                $this->db->query($sql_del);
                $sql = $load."TBPROD.TXT' INTO TABLE data20".$year.".tabprod FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\r\n' SET NOCAB='".$nocab."'";
                $this->db->query($sql);
                $msg[]= 'TBPROD.TXT'.' found and uploaded<br />';
                /*
                echo "<pre>";
                print_r($sql_del);
                print_r($sql);
                echo "</pre>";
                */
            }
            else {
                $msg[]= 'tbprod.TXT'.' not found<BR />';
            }
            //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/tbrayo~1.TXT';
            $file='./assets/uploads/unzip/'.$nocab.'/TBRAYO~1.TXT';
            if(is_file($file))
            {
               $sql_del='delete from data20'.$year.'.tbrayon where nocab='.'"'.$nocab.'"';
               $this->db->query($sql_del);
               $sql = $load."TBRAYO~1.TXT' INTO TABLE data20".$year.".tbrayon FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\r\n' SET NOCAB='".$nocab."'";
               $this->db->query($sql);
               $msg[]= 'tbrayo~1.TXT'.' found and uploaded<br />';
               /*
               echo "<pre>";
               print_r($sql_del);
               print_r($sql);
               echo "</pre>";
               */
            }
            else {
               $msg[]= 'tbrayo~1.TXT'.' not found<BR />';
            }

            $sql_del='delete from data20'.$year.'.fi where nodokjdi="XXXXXX"';
            //$sql_del='delete from data'.$year.'.fh where nodokjdi="XXXXXX"';
            $this->db->query($sql_del);
            $sql_upd="update data20".$year.".tblang set custid=concat(nocab,kode_lang),compid=concat(kode_comp,kode_lang) where nocab=".'".$nocab."';
            $this->db->query($sql_upd);
            /*
            $sql_pil_fi="update data20".$year.".fi set kodeprod='020002' where kodeprod='020002 P'"; 
            $this->db->query($sql_pil_fi);
            $sql_pil_ri="update data20".$year.".ri set kodeprod='020002' where kodeprod='020002 P'"; 
            
            $this->db->query($sql_pil_ri);
            $sql_madu_fi="update data20".$year.".fi set kodeprod='060009' where kodeprod='06009'";
            $this->db->query($sql_madu_fi);
            $sql_madu_ri="update data20".$year.".ri set kodeprod='060009' where kodeprod='06009'";
            $this->db->query($sql_madu_ri);
            */
            $delete00fi = "delete from data20".$year.".fi where (left(kodeprod,2)='00' or left(kodeprod,3)='80' or left(kodeprod,2)='90')";
            $this->db->query($delete00fi);
            $delete00ri = "delete from data20".$year.".ri where (left(kodeprod,2)='00' or left(kodeprod,3)='80' or left(kodeprod,2)='90')";
            $this->db->query($delete00ri);

            //update kolom status di tabel mpm.upload
            $data['id'] = $id_upload;
            $x = $this->hitung_omzet2($data);
            echo "<pre>";
            print_r($x);
            echo "</pre>";

            $sql_madu_ri="update mpm.upload set status='1', omzet = ".'"'.$x.'"'." where id=$id_upload";
            
            $proses = $this->db->query($sql_madu_ri);

            echo "proses : ".$proses;
            if ($proses == '1') {
                echo "berhasil";
                //redirect('proses_data/view_upload_all_dp');
            }else
            {
                echo "ada kesalahan, silahkan ulangi";
                //redirect('proses_data/view_upload_all_dp','refresh');
            }

        
            
    

        
            
    }

    public function proses_data($data){

        $nocab = $data['nocab'];
        $year = substr($data['tahun'],2,2);
        $month = $data['bulan'];
        $lastupload = $data['lastupload'];
        $userid = $this->session->userdata('id');

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
                      'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','st','qh','qi','td','tg','th','ti','tr','tu','vh','vi'
                    );
        $ddl2 = array('tblang','tbkota');
        $ddl3 = array('tabsupp','tabsales','tabsalur','tabtype','norayon','tabfaktr','tabgrupp');        
        $load="LOAD DATA INFILE 'C:/xampp/htdocs/cisk/assets/uploads/unzip/".$nocab."/";
            foreach($ddl1 as $ddl)
            {
                $fields = $this->db->field_data("db_upload.".$ddl);
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
                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.$year.$month.'.TXT';
                $cek = file_exists($file);
                if(file_exists($file))
                {
                    if($ddl=='st')
                    {
                        $sql_del="delete from db_upload.$ddl where nocab='$nocab'";
                        $this->db->query($sql_del);
                        //echo "<pre>sql_del : ".$sql_del."</pre>";
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$year$month' ".$set;    
                        $this->db->query($sql);
                        //echo "<pre>sql : ".$sql."</pre>";
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';

                    }
                    else
                    {
                        $sql_del="delete from db_upload.$ddl where nocab='$nocab'";
                        $this->db->query($sql_del); 
                        //echo "<pre>sql_del : ".$sql_del."</pre>";  
                        //$sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$month' ".$set;
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        $this->db->query($sql);
                        //$sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        //echo "<pre>sql : ".$sql."</pre>";
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';
                    }
                }
                else {
                    
                }
            }

            foreach($ddl2 as $ddl)
            {
                $fields = $this->db->field_data("db_upload.".$ddl);
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
                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.'.TXT';
                if(is_file($file))
                {
                    $sql_del = "delete from db_upload.$ddl where nocab = '$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load.strtoupper($ddl).$nocab.".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab' ".$set;
                    $proses = $this->db->query($sql);
                }
                else {
                }
            }
            $sql_del="delete from db_upload.tblang where nocab='$nocab' and kode_lang =''";
            $this->db->query($sql_del);
            foreach($ddl3 as $ddl)
            {
                $fields = $this->db->field_data("db_upload.".$ddl);
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
                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).'.TXT';
                if(is_file($file))
                {
                    $sql_del="delete from db_upload.$ddl where nocab='$nocab'";
                    $this->db->query($sql_del);                    
                    $sql = $load.strtoupper($ddl).".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab' ".$set;
                    $this->db->query($sql);
                }
                else {
                }
            }  

            $file='./assets/uploads/unzip/'.$nocab.'/TBPROD.TXT';
            if(is_file($file))
            {
                $sql_del="delete from db_upload.tabprod where nocab='$nocab'";
                $this->db->query($sql_del);
                $sql = $load."TBPROD.TXT' INTO TABLE db_upload.tabprod FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB='$nocab'";
                $this->db->query($sql);
            }
            else {
                
            }
            $file='./assets/uploads/unzip/'.$nocab.'/TBRAYO~1.TXT';
            if(is_file($file))
            {
               $sql_del="delete from db_upload.tbrayon where nocab='$nocab'";
               $this->db->query($sql_del);
               $sql = $load."TBRAYO~1.TXT' INTO TABLE db_upload.tbrayon FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB=''";
               $this->db->query($sql);
            }
            else {
               $msg[]= 'tbrayo~1.TXT'.' not found<BR />';
            }

            $sql_del="delete from db_upload.fi where nodokjdi='XXXXXX'";
            $this->db->query($sql_del);
            $sql_upd="update db_upload.tblang set custid=concat(nocab,kode_lang),compid=concat(kode_comp,kode_lang) where nocab='$nocab'";
            $this->db->query($sql_upd);
            $delete00fi = "delete from db_upload.fi where (left(kodeprod,2)='00' or left(kodeprod,3)='80' or left(kodeprod,2)='90')";
            $this->db->query($delete00fi);
            $delete00ri = "delete from db_upload.ri where (left(kodeprod,2)='00' or left(kodeprod,3)='80' or left(kodeprod,2)='90')";
            $this->db->query($delete00ri);
            $sql_del="delete from db_upload.fi where kodeprod like '9%' or kodeprod like '8%'";
            $this->db->query($sql_del);
            $sql_del="delete from db_upload.ri where kodeprod like '9%' or kodeprod like '8%'";
            $this->db->query($sql_del);

            //update kolom status di tabel mpm.upload
            $data['lastupload'] = $lastupload;
            $x = $this->hitung_omzet3($data);
            $sql_madu_ri="update mpm.upload set status='0' where lastupload='$lastupload'";
            $proses = $this->db->query($sql_madu_ri);

            $sql = "select id from mpm.upload where userid = $userid and lastupload='$lastupload'";
            $query = $this->db->query($sql);
            foreach ($query->result() as $row) {
                $id = $row->id;
                //echo "id : ".$id;
            } 

            //echo "proses : ".$proses;
            if ($proses == '1') {
                //return $x;
                
                return array(
                    'omzet' => $x,
                    'id' => $id,
                );
            }else
            {
                echo "ada kesalahan, silahkan ulangi";
                //redirect('proses_data/view_upload_all_dp','refresh');
            }

    }

    public function proses_data_jbr($data){

        $nocab = $data['nocab'];
        $year = substr($data['tahun'],2,2);
        $month = $data['bulan'];
        $lastupload = $data['lastupload'];
        $userid = $this->session->userdata('id');

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
                      'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','st','qh','qi','td','tg','th','ti','tr','tu','vh','vi'
                    );
        $ddl2 = array('tblang','tbkota');
        $ddl3 = array('tabsupp','tabsales','tabsalur','tabtype','norayon','tabfaktr','tabgrupp');        
        $load="LOAD DATA INFILE 'C:/xampp/htdocs/cisk/assets/uploads/unzip/".$nocab."/";
            foreach($ddl1 as $ddl)
            {
                $fields = $this->db->field_data("db_upload_jbr.".$ddl);
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
                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.$year.$month.'.TXT';
                $cek = file_exists($file);
                if(file_exists($file))
                {
                    if($ddl=='st')
                    {
                        $sql_del="delete from db_upload_jbr.$ddl where nocab='$nocab'";
                        $this->db->query($sql_del);
                        //echo "<pre>sql_del : ".$sql_del."</pre>";
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload_jbr.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$year$month' ".$set;    
                        $this->db->query($sql);
                        //echo "<pre>sql : ".$sql."</pre>";
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';

                    }
                    else
                    {
                        $sql_del="delete from db_upload_jbr.$ddl where nocab='$nocab'";
                        $this->db->query($sql_del); 
                        //echo "<pre>sql_del : ".$sql_del."</pre>";  
                        //$sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$month' ".$set;
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload_jbr.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        $this->db->query($sql);
                        //$sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        //echo "<pre>sql : ".$sql."</pre>";
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';
                    }
                }
                else {
                    
                }
            }

            foreach($ddl2 as $ddl)
            {
                $fields = $this->db->field_data("db_upload_jbr.".$ddl);
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
                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.'.TXT';
                if(is_file($file))
                {
                    $sql_del = "delete from db_upload_jbr.$ddl where nocab = '$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load.strtoupper($ddl).$nocab.".TXT' INTO TABLE db_upload_jbr.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab' ".$set;
                    $proses = $this->db->query($sql);
                }
                else {
                }
            }
            $sql_del="delete from db_upload_jbr.tblang where nocab='$nocab' and kode_lang =''";
            $this->db->query($sql_del);
            foreach($ddl3 as $ddl)
            {
                $fields = $this->db->field_data("db_upload_jbr.".$ddl);
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
                $file='./assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).'.TXT';
                if(is_file($file))
                {
                    $sql_del="delete from db_upload_jbr.$ddl where nocab='$nocab'";
                    $this->db->query($sql_del);                    
                    $sql = $load.strtoupper($ddl).".TXT' INTO TABLE db_upload_jbr.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab' ".$set;
                    $this->db->query($sql);
                }
                else {
                }
            }  

            $file='./assets/uploads/unzip/'.$nocab.'/TBPROD.TXT';
            if(is_file($file))
            {
                $sql_del="delete from db_upload_jbr.tabprod where nocab='$nocab'";
                $this->db->query($sql_del);
                $sql = $load."TBPROD.TXT' INTO TABLE db_upload_jbr.tabprod FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB='$nocab'";
                $this->db->query($sql);
            }
            else {
                
            }
            $file='./assets/uploads/unzip/'.$nocab.'/TBRAYO~1.TXT';
            if(is_file($file))
            {
               $sql_del="delete from db_upload_jbr.tbrayon where nocab='$nocab'";
               $this->db->query($sql_del);
               $sql = $load."TBRAYO~1.TXT' INTO TABLE db_upload_jbr.tbrayon FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB=''";
               $this->db->query($sql);
            }
            else {
               $msg[]= 'tbrayo~1.TXT'.' not found<BR />';
            }

            $sql_del="delete from db_upload_jbr.fi where nodokjdi='XXXXXX'";
            $this->db->query($sql_del);
            $sql_upd="update db_upload_jbr.tblang set custid=concat(nocab,kode_lang),compid=concat(kode_comp,kode_lang) where nocab='$nocab'";
            $this->db->query($sql_upd);
            $delete00fi = "delete from db_upload_jbr.fi where (left(kodeprod,2)='00' or left(kodeprod,3)='80' or left(kodeprod,2)='90')";
            $this->db->query($delete00fi);
            $delete00ri = "delete from db_upload_jbr.ri where (left(kodeprod,2)='00' or left(kodeprod,3)='80' or left(kodeprod,2)='90')";
            $this->db->query($delete00ri);

            //update kolom status di tabel mpm.upload
            $data['lastupload'] = $lastupload;
            $x = $this->hitung_omzet3($data);
            $sql_madu_ri="update mpm.upload set status='0' where lastupload='$lastupload'";
            $proses = $this->db->query($sql_madu_ri);

            $sql = "select id from mpm.upload where userid = $userid and lastupload='$lastupload'";
            $query = $this->db->query($sql);
            foreach ($query->result() as $row) {
                $id = $row->id;
                //echo "id : ".$id;
            } 

            //echo "proses : ".$proses;
            if ($proses == '1') {
                //return $x;
                
                return array(
                    'omzet' => $x,
                    'id' => $id,
                );
            }else
            {
                echo "ada kesalahan, silahkan ulangi";
                //redirect('proses_data/view_upload_all_dp','refresh');
            }

    }

    public function submitOmzet($data)
    {
        //$prosesData = $this->load->database('prosesData',TRUE);
        $nocab = $data['nocab'];
        $tahun = $data['tahun'];
        $tahun_stock = substr($tahun,2,2);
        $bulan = $data['bulan'];
        $id = $this->uri->segment('6');

        $sql = "
            delete from data$tahun.fi
            where bulan = $bulan and nocab = '$nocab'
        ";
        $proses_fi_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.fi
            select * from db_upload.fi
            where bulan = $bulan and nocab = '$nocab'
        "; 
        $proses_fi = $this->db->query($sql);
        $sql = "
            delete from data$tahun.ri
            where bulan = $bulan and nocab = '$nocab'
        ";
        $proses_ri_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.ri
            select * from db_upload.ri
            where bulan = $bulan and nocab = '$nocab'
        "; 
        $proses_ri = $this->db->query($sql);
        $sql = "
            delete from data$tahun.fh
            where bulan = $bulan and nocab = '$nocab'
        ";
        $proses_fh_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.fh
            select * from db_upload.fh
            where bulan = $bulan and nocab = '$nocab'
        "; 
        $proses_fh = $this->db->query($sql);
        $sql = "
            delete from data$tahun.rh
            where bulan = $bulan and nocab = '$nocab'
        ";
        $proses_rh_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.rh
            select * from db_upload.rh
            where bulan = $bulan and nocab = '$nocab'
        "; 
        $proses_rh = $this->db->query($sql);
        $sql = "
            delete from data$tahun.st
            where bulan = $tahun_stock$bulan and nocab = '$nocab'
        ";
        $proses_st_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.st
            select * from db_upload.st
            where bulan = $tahun_stock$bulan and nocab = '$nocab'
        "; 
        $proses_st = $this->db->query($sql);
        
        $sql = "
            delete from data$tahun.tabsales
            where nocab = '$nocab'
        ";
        
        $proses_tabsales_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.tabsales
            select * from db_upload.tabsales
            where nocab = '$nocab'
        "; 
        $proses_tabsales = $this->db->query($sql);

        $sql = "
            delete from data$tahun.tabtype
            where nocab = '$nocab'
        ";        
        $proses_tabtype_del = $this->db->query($sql);
        
        $sql = "
            insert into data$tahun.tabtype
            select * from db_upload.tabtype
            where nocab = '$nocab'
        "; 
        $proses_tabtype = $this->db->query($sql);


        $sql = "
            delete from data$tahun.tblang
            where nocab = '$nocab'
        ";
        $proses_tblang_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.tblang
            select * from db_upload.tblang
            where nocab = '$nocab'
        "; 
        $proses_tblang = $this->db->query($sql);        
        $sql = "
        delete from data$tahun.tbrayon
        where nocab = '$nocab'
        ";
        $proses_tbrayon_del = $this->db->query($sql);
        $sql = "
            insert into data$tahun.tbrayon
            select * from db_upload.tbrayon
            where nocab = '$nocab'
        "; 
        $proses_tbrayon = $this->db->query($sql);
        echo "<pre>";
        if($proses_fi_del){
            echo "1";
        }else{ echo "0";            
        }

        if($proses_fi){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_ri_del){
            echo "1";
        }else{ echo "0";            
        }

        if($proses_ri){
            echo "1";
        }else{ echo "0";           
        }

        if($proses_fh_del){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_fh){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_rh_del){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_rh){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_st_del){
           print_r($proses_st_del);
           print_r($proses_st);
        }else{ echo "0";             
        }

        if($proses_st){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_tabsales_del){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_tabsales){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_tblang_del){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_tblang){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_tbrayon_del){
            echo "1";
        }else{ echo "0";             
        }

        if($proses_tbrayon){
            echo "1";
        }else{ echo "0";             
        }
        


        echo "</pre>";
        $x = $this->hitung_omzet4($id);
        $sql="update mpm.upload set status='1', omzet = ".'"'.$x.'"'." where id='$id'";
        $proses = $this->db->query($sql);
        $pesan_berhasil = "data anda sudah berhasil masuk ke database kami. terima kasih";
        return $pesan_berhasil;
        
    }
    
}

/* End of file model_proses_data.php */
/* Location: ./application/models/model_proses_data.php */