<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadnew extends MY_Controller {

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

    function Uploadnew()
    {
        set_time_limit(0);
        ini_set('mysql.connect_timeout', 3000);
        ini_set('default_socket_timeout', 3000);
       
        $this->load->helper(array('form', 'url','file'));
        $this->load->library('template');

        //query untuk menampilkan menu dinamis
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
            $data['page_content']='upload/upload_form_view';
            $data['menu']=$this->db->query($this->querymenu);
            $sql='select date_format(max(lastupload),"%d %M %Y, %T")as last from mpm.upload where userid=?';
            $query=$this->db->query($sql,array($this->session->userdata('id')));
            $data['upl']=$query->row();            
            $data['url'] = site_url('upload');
            $data['error']='';
            $this->template($data['page_content'],$data);
            //$this->load->view('upload_form', array('error' => ' ' ));
    }

   function file_upload()
    {
        $logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            if(!is_dir('./assets/uploads/zip/'.date('Ym').'/'))
            {
                @mkdir('./assets/uploads/zip/'.date('Ym').'/',0777);
            }
        echo $logged_in;

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

            //judul halaman
            echo "<h1>Upload File</h1><hr>";

            //mengambil no cabang dari orig_name => 96
            $upload_data = $this->upload->data();
            $nocab = substr($upload_data['orig_name'],2,2);
            $year  = $this->input->post('year');
            $month = substr($upload_data['orig_name'],4,2);
            echo "no cabang : ".$nocab.'<br />';
            echo 'tahun'.$year.'<br />';
            echo 'bulan : '.$month.'<br />';
            $filename = substr($upload_data['orig_name'],0,8);
            echo $filename;

            
            //proses ekstraksi zip
            $openZip = $zip->open($file);
            echo "<br>".$file."<br>";
            /*
            if ($openZip === TRUE) 
            {   
                echo "status openzip : ".$openZip."<br>";
                if ($zip->setPassword("DELTOMED"))
                {                 
                    if (!$zip->extractTo('./assets/uploads/unzip/'.$nocab.''))
                       echo "Extraction failed (wrong password?)";
                }else{
                    echo "gagal ekstract"
                }

            $zip->close();
            }
            */


            if ($openZip === TRUE) 
            {   
                echo "status openzip : ".$openZip."<br>";
              
                if (!$zip->extractTo('./assets/uploads/unzip/'.$nocab.''))
                   echo "Extraction failed (wrong password?)";


            $zip->close();
            }



            else
            {
                die("Failed opening archive: ". @$zip->getStatusString() . " (code: ". $zip_status .")");
            }

            $path_load = str_replace('\\','/',realpath('.'))."/assets/uploads/unzip/$nocab/";
            echo "path load : ".$path_load."<br>";
            $dir    = $path_load;   
            $files2 = scandir($dir, 1);

            echo "files2 : ".$files2."<br>";

           

            //fungsi perulangan array
            for ($i=0;$i<count($files2);$i++){
                //mengambil 2 huruf awal dari file name
                $b = substr($files2[$i], 0,2);
                echo "".$b."<br>";

                //mencari file yang sesuai untuk di load ke database
                    

                    if ($b == "FI")
                        {
                            //echo "<strong>File yang berawalan huruf FI yaitu : ".$files2[$i]."</strong><br>";
                            $tabel = "fi";  
                            echo "Loading data : ".$files2[$i];

                            $file_path = $path_load.$files2[$i].'';
                            //echo '$FILE_PATH : '.$file_path.'<br>';
                            $sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
                            $this->db->query($sql_del);                  

                            $load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
                            if ($load){
                                echo(".. Success ..");
                                } else {
                                echo(".. Failed .. Please check your connection and Try Again");
                                }
                            echo "<br />";
                        }

                    elseif ($b == "RI")
                        {
                            //echo "<strong>File yang berawalan huruf RI yaitu : ".$files2[$i]."</strong><br>";
                            $tabel = "ri";  
                            echo "Loading data : ".$files2[$i];

                            $file_path = $path_load.$files2[$i].'';
                            //echo '$FILE_PATH : '.$file_path.'<br>';
                            $sql_del="delete from data$year.$tabel where nocab='$nocab' and bulan='$month'";
                            $this->db->query($sql_del);                  

                            $load=mysql_query("LOAD DATA INFILE '$file_path' REPLACE INTO TABLE data$year.$tabel FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB = '$nocab', BULAN = '$month'") or die(mysql_error());
                            if ($load){
                                echo(".. RI Success ..");
                                } else {
                                echo(".. Failed .. Please check your connection and Try Again");
                                }
                            echo "<br />";
                        }

                    

                    
                    
                    

                    

                    else
                        {
                            //echo "File etc : ".$files2[$i]."</strong><br>"; 
                        }
            }
            //echo "<br>";

        }

        //$data['msg']=$this->console($upload_data['orig_name']);
             $sql='select format(sum(val),2) val from(
                select sum(tot1) val from data'.$year.'.fi where bulan='.$month.' and nocab='.$nocab.'
                union ALL
                select sum(tot1) val from data'.$year.'.ri where bulan='.$month.' and nocab='.$nocab.'
                )a';
            $query=$this->db->query($sql);
            $row=$query->row();
            $data['omzet']=$row->val;
            echo  '<h1>Omzet saat ini :<strong> Rp '.$data['omzet'].'</strong></h1>';
            echo '<h3>Jika ada ketidaksesuaian omzet atau hal lainnya, mohon infokan kepada kami (Bagian IT)</h3>';
        delete_files($path_load);

        $this->load->view('upload/upload_success_view');

        //redirect(base_url().'sinkronisasi');

        
    }
    
    function sinkronisasi_omzet()
    {
        //sinkronisasi omzet ke mpm.tbl_omzet
        $nocab=$this->session->userdata('nocab');
        //cek record
        $cek_record = $this->db->query('select * from mpm.tbl_omzet where nocab ='.$nocab.'');

        if($cek_record->num_rows() > 0)
        {   
           $delete_record = $this->db->query('delete from mpm.tbl_omzet where nocab ='.$nocab.'');
           //echo " nocab : ".$nocab."<br>";
           //echo " delete record : ".$delete_record."<br>";

           //echo "ada";
           $query = "
            insert into mpm.tbl_omzet
            select  '',a.kode_comp,
                    nocab,
                    nama_comp,
                    sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
                    sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
                    sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
                    sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
                    sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
                    sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
                    sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
                    sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
                    sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
                    sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
                    sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
                    sum(if(`blndok` = 12, `unit`, 0)) as `b12`
            from
            (
                    select      KODE_COMP,`nocab`, `blndok`, sum(`tot1`) as `unit`
                    from            data2015.fi
                    where       nodokjdi <> 'XXXXXX'
                    group by    nocab,`blndok`

                    union all

                    select          KODE_COMP,`nocab`, `blndok`, sum(`tot1`) as `unit`
                    from                data2015.ri
                    where           nodokjdi <> 'XXXXXX'
                    group by        nocab,`blndok`              
            )a
            
            inner join mpm.tabcomp b USING (nocab)
            where a.nocab = $nocab
            GROUP BY nocab

        ";

        $proses = $this->db->query($query);
        
        }else{
            $query = "
            insert into mpm.tbl_omzet
            select  '',a.kode_comp,
                    nocab,
                    nama_comp,
                    sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
                    sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
                    sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
                    sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
                    sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
                    sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
                    sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
                    sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
                    sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
                    sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
                    sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
                    sum(if(`blndok` = 12, `unit`, 0)) as `b12`
            from
            (
                    select      KODE_COMP,`nocab`, `blndok`, sum(`tot1`) as `unit`
                    from            data2015.fi
                    where       nodokjdi <> 'XXXXXX'
                    group by    nocab,`blndok`

                    union all

                    select          KODE_COMP,`nocab`, `blndok`, sum(`tot1`) as `unit`
                    from                data2015.ri
                    where           nodokjdi <> 'XXXXXX'
                    group by        nocab,`blndok`              
            )a
    
            inner join mpm.tabcomp b USING (nocab)
            where a.nocab = $nocab
            GROUP BY nocab
            ";

            $proses = $this->db->query($query);
        }
    }

    public function cek_session()
    {
        $supp=$this->session->userdata('supp');
        $username=$this->session->userdata('username');
        $level=$this->session->userdata('level');
        $naper=$this->session->userdata('naper');
        $nocab=$this->session->userdata('nocab');
        echo "supp : ".$supp."<br>";
        echo "username : ".$username."<br>";
        echo "level : ".$level."<br>";
        echo "naper : ".$naper."<br>";
        echo "nocab : ".$nocab."<br>";
    }
}
?>