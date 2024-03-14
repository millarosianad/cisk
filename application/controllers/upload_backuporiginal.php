<?php

class Upload extends MY_Controller
{

        var $querymenu='';
        var $source ='';
        var $finale = '';
        var $folder='./assets/uploads/zip/';
      
        private function template($view,$data)
        {
            $this->template->set_title('MPM SQUARE');
            $this->template->add_js('modules/skeleton.js');
            $this->template->add_css('modules/skeleton.css');
            $this->template->load_view($view, $data);
        }
	function Upload()
	{
            set_time_limit(0);
            ini_set('mysql.connect_timeout', 3000);
            ini_set('default_socket_timeout', 3000);
           
            $this->load->helper(array('form', 'url','file'));
            $this->load->library('template');
            $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview ';
	
            $this->source=str_replace('\\','/',realpath('.'))."/assets/uploads/";//base_url().'assets/uploads/';
            $this->finale=str_replace('\\','/',realpath('.'))."/assets/uploads/unzip/";//base_url().'assets/uploads/unzip/';
        }
	function index()
	{
            $logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            $data['page_title'] = 'Welcome to MPM Distribution System';
            $data['page_content']='upload/upload_form';
            $data['menu']=$this->db->query($this->querymenu);
            $sql='select date_format(max(lastupload),"%d %M %Y, %T")as last from mpm.upload where userid=?';
            $query=$this->db->query($sql,array($this->session->userdata('id')));
            $data['upl']=$query->row();
            $data['url'] = site_url('upload');
            $data['error']='';
            $this->template($data['page_content'],$data);
            //$this->load->view('upload_form', array('error' => ' ' ));
            //echo "test";
	}
	function do_upload()
	{
		$logged_in= $this->session->userdata('logged_in');
                if(!isset($logged_in) || $logged_in != TRUE)
                {
                    redirect('login/','refresh');
                }
                if(!is_dir($this->folder.date('Ym').'/'))
                {
                //mkdir($this->finale.strstr($filename,2,2),0700);
                    @mkdir($this->folder.date('Ym').'/',0777);
                }
                $config['upload_path'] = $this->folder.date('Ym').'/';
		$config['allowed_types'] = 'zip';
		$config['max_size']	= '10000';
                $config['overwrite']	= TRUE;

		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = $this->upload->display_errors();
                        $data['page_title'] = 'Upload Data';
                        $data['page_content']='upload/upload_form';
                        $data['menu']=$this->db->query($this->querymenu);
                        $data['url'] = site_url('upload');
                        $data['error']=$error;
                        $this->template($data['page_content'],$data);
			//$this->load->view('upload_form', $error);
		}
		else
		{
			$upload_data = $this->upload->data();
                        $year  = $this->input->post('year');
                        $month = substr($upload_data['orig_name'],4,2);
                        $nocab = substr($upload_data['orig_name'],2,2);
                       
                        
                        $data['page_title'] = 'Upload Data';
                        $data['page_content']='upload/upload_success';
                        $data['menu']=$this->db->query($this->querymenu);
                        $data['url'] = site_url('upload');
                        $data['upload_data']=$upload_data;
                        $this->unzip($upload_data['orig_name']);
                        $data['msg']=$this->console($upload_data['orig_name']);
                         $sql='select format(sum(val),2) val from(
                            select sum(tot1) val from data'.$year.'.fi where bulan='.$month.' and nocab='.$nocab.'
                            union ALL
                            select sum(tot1) val from data'.$year.'.ri where bulan='.$month.' and nocab='.$nocab.'
                            )a';
                        $query=$this->db->query($sql);
                        $row=$query->row();
                        $data['omzet']=$row->val;
                        $this->delete_files(substr($upload_data['orig_name'],2,2));
                        $this->template($data['page_content'],$data);
                        
			//$this->load->view('upload_success', $upl);
		}
	}
       
        private function unzip($filename)
        {
            //$nocab=substr($filename,2,2);
            //mkdir($this->source.'//zip//'.$nocab);

            if(!is_dir('./assets/uploads/unzip/'.substr($filename,2,2).'/'))
            {
                //mkdir($this->finale.strstr($filename,2,2),0700);
                mkdir('./assets/uploads/unzip/'.substr($filename,2,2).'/',0777);
            }
            //$zip = $this->source.'prog\pkunzip.exe'.' -o -sDELTOMED '. $this->source."zip\\".date("Ym")."\\".$filename.' '.$this->finale.substr($filename,2,2);
            //echo date('Y',filemtime($this->source.$filename));
            //exec($zip);

            // Optional: Only take out these files, anything else is ignored
            //$this->unzip->allow(array('txt'));

            // Give it one parameter and it will extract to the same folder
            //$this->unzip->extract('uploads/my_archive.zip');

            // or specify a destination directory
            //@$this->unzip->extract('./assets/uploads/zip/'.date("Ym").'/'.$filename, './assets/uploads/unzip/'.substr($filename,2,2).'/');
            //echo chmod('./assets/uploads/unzip/'.substr($filename,2,2),0777);
            shell_exec('unzip -P DELTOMED ./assets/uploads/zip/'.date('Ym').'/'.substr($filename,0,-4).' -d ./assets/uploads/unzip/'.substr($filename,2,2).'/ 2>&1');

        }
        private function delete_files($nocab)
        {
            //delete_files('./assets/uploads/zip');
            delete_files('./assets/uploads/unzip/'.$nocab.'/');
            $sql="delete from upload where length(filename)!=12";
            $this->db->query($sql);
            //unlink($filename);
        }
        private function getFileExtension($filename){
        return substr($filename, strrpos($filename, '.'));
        }
        public function console_all($year='')
        {
            $logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            set_time_limit(0);
            //$year='2011';
            $count=0;
            //$handle = opendir('D:/wamp/www/ci/assets/uploads/zip/');
            $handle = opendir('./assets/uploads/zip/');
            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    if($file != "." && $file != "..") {
                    //echo "$file\n";
                    if ($this->getFileExtension($file)=='.ZIP'||getFileExtension($file)=='.zip'){
                    $buffer[]=$file;
                    $count++;
                        }
                    }
                }
            closedir($handle);
            }
            foreach($buffer as $buff)
            {
                $this->unzip($buff);
                $this->console($buff,$year);
                //$this->delete_files();
                $message[]='<b>'.$buff.' </b>,success consoled <br />';
            }
            $data['page_title'] = 'Upload Data';
            $data['page_content']='upload/console_all';
            $data['menu']=$this->db->query($this->querymenu);
            $data['url'] = site_url('upload');
            $data['message']=$message;
            //$data['upload_data']=$upload_data;
            $this->template($data['page_content'],$data);
        }
        public function alter_all()
        {
            $logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            set_time_limit(0);
            $ddl1 = array('bd','bg','bp','eh','ei','fi','fh','gh','gi','ko','kr','ld','lh','mh',
                          'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','st','qh','qi',
                          'td','tg','th','ti','tr','tu','vh','vi','sk'
                        );
            $field='TGL_EDIT';
            foreach($ddl1 as $ddl){
                $sql='select '.$field.' from masterdata.'.$ddl;
                if(mysql_query($sql))
                {
                    $sql='alter table masterdata.'.$ddl.' modify  '.$field.' varchar(10)';
                    $this->db->query($sql);
                    echo $ddl.' is modified <br />';
                }
            }
        }

        
        
        public function show_test()
        {
             $data['page_title'] = 'Upload Data';
             $data['page_content']='test';
             //$data['menu']=$this->db->query($this->querymenu);
             $data['url'] = site_url('upload');
             $data['msg'] = $this->test();
             $this->template($data['page_content'],$data);
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
            /*$ddl1 = array('BD','BG','BP','EH','EI','FI','FH','GH','GI','KO','KR','LD','LH','MH',
                          'MP','OD','OH','PB','PH','RI','RH','SD','SH','SP','SO','ST','QH','QI',
                          'TD','TG','TH','TI','TR','TU','VH','VI'
                        );
            $ddl2 = array('TBLANG','TBKOTA');
            $ddl3 = array('TABSUPP','TABSALES','TABSALUR','TABTYPE','NORAYON','TABFAKTR','TABGRUPP');*/
            $ddl1 = array('bd','bg','bp','eh','ei','fi','fh','gh','gi','ko','kr','ld','lh','mh',
                          'mp','od','oh','pb','ph','ri','rh','sd','sh','sp','so','sk','st','qh','qi',
                          'td','tg','th','ti','tr','tu','vh','vi'
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
            //$load="LOAD DATA INFILE '".str_replace('\\','/',realpath('.'))."/assets/uploads/unzip/".$nocab."/";
            $load="LOAD DATA INFILE '".realpath('.')."/assets/uploads/unzip/".$nocab."/";
            //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/'.$ddl.$nocab.$year.$month.'.TXT';
            foreach($ddl1 as $ddl)
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
                //$file='D:/wamp/www/ci/assets/uploads/unzip/'.$nocab.'/'.$ddl.$nocab.$year.$month.'.TXT';
                //$load="LOAD DATA INFILE '".str_replace('\\','/',realpath('.'))."/assets/uploads/unzip/".$nocab."/";
                //$file=str_replace('\\','/',realpath('.')).'/assets/uploads/unzip/'.$nocab.'/'.$ddl.$nocab.$year.$month.'.TXT';

                $file=realpath('.').'/assets/uploads/unzip/'.$nocab.'/'.strtoupper($ddl).$nocab.$year.$month.'.TXT';
               
                if(file_exists($file))
                {
                    if($ddl=='st')
                    {
                        $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.$nocab." and bulan =".$year.$month;
                        $this->db->query($sql_del);
                        //$sql = "LOAD DATA INFILE 'D:/wamp/www/ci/assets/uploads/unzip/".$nocab."/".$ddl.$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        //$sql = "LOAD DATA INFILE '".str_replace('\\','/',realpath('.'))."/assets/uploads/unzip/".$nocab."/".$ddl.$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$year.$month."' ".$set;
                        $this->db->query($sql);
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';
                    }
                    else
                    {
                        $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.$nocab." and bulan =".$month;
                        $this->db->query($sql_del);
                        //$sql = "LOAD DATA INFILE 'D:/wamp/www/ci/assets/uploads/unzip/".$nocab."/".$ddl.$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        //$sql = "LOAD DATA INFILE '".str_replace('\\','/',realpath('.'))."/assets/uploads/unzip/".$nocab."/".$ddl.$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                        $this->db->query($sql);
                        $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' found and uploaded <br />';
                    //$this->delete_files($file);
                    }
                }
                else {
                    $msg[]= $ddl.$nocab.$year.$month.'.TXT'.' not found<BR />';
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
                if(is_file($file))
                {
                    $sql_del='delete from data20'.$year.'.'.$ddl.' where nocab='.$nocab;
                    $this->db->query($sql_del);
                    $sql = $load.strtoupper($ddl).$nocab.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."' ".$set;
                    $this->db->query($sql);
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
            $sql_upd="UPDATE data20".$year.".tblang set custid=concat(nocab,kode_lang),compid=concat(kode_comp,kode_lang) where nocab=".$nocab;
            $this->db->query($sql_upd);
            $sql_pil_fi="update data20".$year.".fi set kodeprod='020002' where kodeprod='020002 P'"; 
            $this->db->query($sql_pil_fi);
            $sql_pil_ri="update data20".$year.".ri set kodeprod='020002' where kodeprod='020002 P'"; 
            
            $this->db->query($sql_pil_ri);
            $sql_madu_fi="update data20".$year.".fi set kodeprod='060009' where kodeprod='06009'";
            $this->db->query($sql_madu_fi);
            $sql_madu_ri="update data20".$year.".ri set kodeprod='060009' where kodeprod='06009'";
            $this->db->query($sql_madu_ri);
            $delete00fi = "DELETE  from data20".$year.".fi where left(kodeprod,2)='00'";
            $this->db->query($delete00fi);
            $delete00ri = "DELETE  from data20".$year.".ri where left(kodeprod,2)='00'";
            $this->db->query($delete00ri);


            
            $upl['userid']=$this->session->userdata('id');
            $upl['lastupload']=date('Y-m-d H:i:s');
            $upl['filename']=$filename;
            $this->db->insert('mpm.upload',$upl);
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
            //$sql = "LOAD DATA INFILE 'C:/WAMP/WWW/unzip/fi".$nocab.$year.$month."' INTO TABLE data".$year.". FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\r\n' SET NOCAB='".getNocab($buff)."'";
            
            return $msg;
        }
}
?>