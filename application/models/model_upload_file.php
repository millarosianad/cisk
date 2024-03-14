<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_upload_file extends CI_Model
{
    public function cek_upload_terakhir($data_controller)
    {
        //echo "ccccccccc";
        $id = $data_controller['id'] . "<br>";

        $this->db->order_by('id', 'desc');
        $this->db->select('');
        $this->db->where('upload.userid', $id);
        $hasil = $this->db->get('mpm.upload', 1);

        if ($hasil->num_rows() > 0) {
            return $hasil->result();
            //print_r($hasil->result());
        } else {
            return array();
        }
    }

    public function cek_kesesuaian_upload($data_controller)
    {
        /* data zip file */
        $id_login = $data_controller['id'];
        $filename_zip = $data_controller['filename'];
        $tanggal_zip = $data_controller['tanggal'];
        $nocab_zip = $data_controller['nocab'];
        $tahun_zip = $data_controller['year'];
        $bulan_zip = $data_controller['month'];
        $status_closing_zip = $data_controller['status_closing'];

        echo "<pre>";
        echo "### cek zip file ### <br>";
        echo "id login : " . $id_login . "<br>";
        echo "filename_zip : " . $filename_zip . "<br>";
        echo "tahun_zip : " . $tahun_zip . "<br>";
        echo "bulan_zip : " . $bulan_zip . "<br>";
        echo "tanggal_zip : " . $tanggal_zip . "<br>";
        echo "status_closing_zip : " . $status_closing_zip . "<br>";
        echo "nocab_zip : " . $nocab_zip . "<br>";
        echo "</pre>";

        $data['filename'] = $filename_zip;
        $data['nocab'] = $nocab_zip;
        $data['year'] = $tahun_zip;
        $data['month'] = $bulan_zip;

        $this->db->order_by('id', 'desc');
        // $this->db->where('upload.flag <>','3');
        // $this->db->where('upload.tahun', $tahun_zip);
        $this->db->where('upload.userid', $id_login);
        $hasil = $this->db->get('mpm.upload', 1);
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $filename_db = $row->filename;
                $lastupload_db = $row->lastupload;
                $tanggal_db = $row->tanggal;
                $bulan_db = $row->bulan;
                $tahun_db = $row->tahun;
                $status_closing_db = $row->status_closing;
                $status = $row->status;
                $flag_check = $row->flag_check;
            }
        } else {
            //return array();
            $filename_db = "";
            $lastupload_db = "";
            $tanggal_db = "";
            $bulan_db = "0";
            $tahun_db = $tahun_zip;
            $status_closing_db = "0";
        }


        echo "<pre>";
        echo "filename_db : " . $filename_db . "<br>";
        echo "lastupload_db : " . $lastupload_db . "<br>";
        echo "tanggal_db : " . $tanggal_db . "<br>";
        echo "bulan_db : " . $bulan_db . "<br>";
        echo "tahun_db : " . $tahun_db . "<br>";
        echo "closing db :" . $status_closing_db . "<br>";
        echo "status :" . $status . "<br>";

        echo "</pre>";

        $user = $this->session->userdata('username');
        $sql = "
            select a.kode_comp, a.nocab
            from mpm.tbl_tabcomp a
            where kode_comp ='$user' and a.active = 1
        ";
        $hasil = $this->db->query($sql)->result();
        foreach($hasil as $a){
            $nocab = $a->nocab;
        }
        // var_dump($nocab);die;
        if($nocab == $nocab_zip ){
            if ($flag_check != 1 && $flag_check != 3 ) {
                if ($tahun_db == $tahun_zip) {
                    if ($bulan_db == $bulan_zip) {
                        if ($status_closing_db == 1 && $status == 1) {
                            $message = "Upload gagal. Bulan ini sudah anda closing saat upload sebelumnya. Silahkan hubungi IT untuk membuka kunci bulan lalu";
                            echo "<script type='text/javascript'>alert('$message');
                                    window.location.href = 'upload_file';
                                    </script>";
                        } else {
                            if ($tanggal_db > $tanggal_zip) {
                                $message = "Upload gagal. Silahkan upload omzet yang mempunyai tanggal lebih besar !";
                                echo "<script type='text/javascript'>alert('$message');
                                    window.location.href = 'upload_file';
                                    </script>";
                            } else {
                                redirect('upload_file/proses_extract_zip/' . $filename_zip . '/' . $nocab_zip . '/' . $tahun_zip . '/' . $bulan_zip . '/' . $tanggal_zip . '/' . $status_closing_zip);
                            }
                        }
                        // }elseif($bulan_db > $bulan_zip){
                        //     echo '<script>alert("Upload gagal. Anda tidak dapat meng-Upload data bulan lalu !");</script>';
                    } else {
                        //harus cek apakah sudah closing bulan sebelumnya
                        if ($status_closing_db == 1 && $status == 1) {
                            redirect('upload_file/proses_extract_zip/' . $filename_zip . '/' . $nocab_zip . '/' . $tahun_zip . '/' . $bulan_zip . '/' . $tanggal_zip . '/' . $status_closing_zip);
                        } else {
                            $message = "Upload gagal. Silahkan UPLOAD dan SUBMIT data CLOSING dan bulan lalu terlebih dahulu !";
                            echo "<script type='text/javascript'>alert('$message');
                                    window.location.href = 'upload_file';
                                    </script>";
                        }
                    }
                } elseif ($tahun_db > $tahun_zip) {
                    $message = "Upload gagal. Anda tidak dapat meng-Upload data tahun lalu !";
                    echo "<script type='text/javascript'>alert('$message');
                            window.location.href = 'upload_file';
                            </script>";
                } else {
                    if ($status_closing_db == 1) {
                        redirect('upload_file/proses_extract_zip/' . $filename_zip . '/' . $nocab_zip . '/' . $tahun_zip . '/' . $bulan_zip . '/' . $tanggal_zip . '/' . $status_closing_zip);
                    } else {
                        $message = "Upload gagal. Silahkan upload data closing bulan lalu terlebih dahulu !";
                        echo "<script type='text/javascript'>alert('$message');
                            window.location.href = 'upload_file';
                            </script>";
                    }
                }
            } else {
                $message = "Data Anda Sedang Di Proses, Silahkan refresh halaman";
                echo "<script type='text/javascript'>alert('$message');
                        window.location.href = 'upload_file';
                        </script>";
            }
        }else{
            $message = "File anda tidak sesuai, Silahkan refresh halaman atau Hubungi team IT";
            echo "<script type='text/javascript'>alert('$message');
                    window.location.href = 'upload_file';
                    </script>";
        }
    }

    public function hitung_omzet3($data_model)
    {

        $userid = $this->session->userdata('id');
        //echo "userid : ".$userid."<br>";

        $lastupload = $data_model['lastupload'];
        //echo "lastupload : ".$lastupload;
        /* cari nilai filename,tahun */
        $this->db->where('lastupload = ' . '"' . $lastupload . '"');
        $query = $this->db->get('mpm.upload', 1);
        foreach ($query->result() as $row) {
            $filename = $row->filename;
            $year = $row->tahun;
            $month = substr($row->filename, 4, 2);
            $nocab = substr($row->filename, 2, 2);
        }
        $sql = '
            select format(sum(val),2) val 
            from
            (
                select sum(tot1) val from db_upload.fi where bulan=' . $month . ' and nocab=' . '"' . $nocab . '"' . '
                union ALL
                select sum(tot1) val from db_upload.ri where bulan=' . $month . ' and nocab=' . '"' . $nocab . '"' . '
            )a';

        //print_r($sql);

        $result = $this->db->query($sql)->row_array();
        return $result['val'];
    }

    public function hitung_omzet4($id)
    {

        $userid = $this->session->userdata('id');
        //echo "userid : ".$userid."<br>";
        // $id = $this->uri->segment('6');

        /* cari nilai filename,tahun */
        $this->db->where('id = ' . '"' . $id . '"');
        $query = $this->db->get('mpm.upload', 1);
        foreach ($query->result() as $row) {
            $filename = $row->filename;
            $year = $row->tahun;
            $month = substr($row->filename, 4, 2);
            $nocab = substr($row->filename, 2, 2);
        }
        $sql = '
            select format(sum(val),2) val 
            from
            (
                select sum(tot1) val from data' . $year . '.fi where bulan=' . $month . ' and nocab=' . '"' . $nocab . '"' . '
                union ALL
                select sum(tot1) val from data' . $year . '.ri where bulan=' . $month . ' and nocab=' . '"' . $nocab . '"' . '
            )a';

        //print_r($sql);

        $result = $this->db->query($sql)->row_array();
        return $result['val'];
    }

    public function proses_data($data)
    {

        $nocab = $data['nocab'];
        $tanggal = $data['tanggal'];
        $year = substr($data['tahun'], 2, 2);
        $month = $data['bulan'];
        $lastupload = $data['lastupload'];
        $userid = $this->session->userdata('id');
        $kode_comp = $this->session->userdata('username');

        // echo "nocab : ".$nocab;
        // die;

        set_time_limit(0);
        ini_set('mysql.connect_timeout', 3000);
        ini_set('default_socket_timeout', 3000);
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $ddl1 = array('fi', 'fh', 'ri', 'rh', 'st', 't_sales_rrk', 'm_sales_salsman_ob');
        $ddl2 = array('tblang', 'tbkota', 'm_customer_creditlimit_prinsipal','m_setup_ppn');
        $ddl3 = array('tabsupp', 'tabsales', 'tabsalur', 'tabtype', 'norayon', 'tabfaktr', 'tabgrupp');
        $load = "LOAD DATA INFILE 'C:/xampp/htdocs/cisk/assets/uploads/unzip/" . $nocab . "/";
        foreach ($ddl1 as $ddl) {
            $fields = $this->db->field_data("db_upload." . $ddl);
            $name = '(';
            $set = ',';
            $i = 1;
            foreach ($fields as $field) {
                if ($field->type == 'date') {
                    $name .= '@date' . $i . ',';
                    $set .= $field->name . '=' . 'str_to_date(@date' . $i . ',"%d-%m-%Y"),';
                    $i++;
                } else {
                    $name .= $field->name . ", ";
                }
            }
            $name = substr($name, 0, -2);
            $name .= ')';
            $set  = substr($set, 0, -1);
            $file = './assets/uploads/unzip/' . $nocab . '/' . strtoupper($ddl) . $nocab . $year . $month . '.TXT';
            $cek = file_exists($file);
            // echo "file : ".$file;
            if (file_exists($file)) {
                if ($ddl == 'st') {
                    $sql_del = "delete from db_upload.$ddl where nocab='$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . $year . $month . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET NOCAB='$nocab', BULAN='$year$month' " . $set;
                    $this->db->query($sql);
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';

                    // $sql_del_st_detail="delete from db_upload.st_detail where nocab='$nocab' and tanggal='$tanggal'";
                    // $this->db->query($sql_del_st_detail);
                    // $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.st_detail FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$year$month' ,TANGGAL='$tanggal' ".$set;    
                    // $this->db->query($sql);

                    // echo $sql_del."<br>";
                    // echo $sql."<br>";

                } elseif ($ddl == 't_sales_rrk') {
                    $sql_del = "delete from db_upload.$ddl where kode_comp = '$kode_comp' and nocab='$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . $year . $month . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET kode_comp= '$kode_comp',NOCAB='$nocab', BULAN='$month' " . $set;
                    $this->db->query($sql);
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';

                    // $sql_del_st_detail="delete from db_upload.st_detail where nocab='$nocab' and tanggal='$tanggal'";
                    // $this->db->query($sql_del_st_detail);
                    // $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.st_detail FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$year$month' ,TANGGAL='$tanggal' ".$set;    
                    // $this->db->query($sql);
                    // echo "<pre>";
                    // echo $sql_del."<br>";
                    // echo $sql."<br>";
                    // echo "</pre>";
                } elseif ($ddl == 'm_sales_salsman_ob') {
                    // echo "ini ob ";
                    $sql_del = "delete from db_upload.$ddl where kode_comp = '$kode_comp' and nocab='$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . $year . $month . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET kode_comp= '$kode_comp',NOCAB='$nocab', BULAN='$month' " . $set;
                    $this->db->query($sql);
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';

                    // $sql_del_st_detail="delete from db_upload.st_detail where nocab='$nocab' and tanggal='$tanggal'";
                    // $this->db->query($sql_del_st_detail);
                    // $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.st_detail FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$year$month' ,TANGGAL='$tanggal' ".$set;    
                    // $this->db->query($sql);
                    // echo "<pre>";
                    // echo $sql_del."<br>";
                    // echo $sql."<br>";
                    // echo "</pre>";
                } else {
                    $sql_del = "delete from db_upload.$ddl where nocab='$nocab'";
                    $this->db->query($sql_del);
                    //echo "<pre>sql_del : ".$sql_del."</pre>";  
                    //$sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$month' ".$set;
                    $sql = $load . strtoupper($ddl) . $nocab . $year . $month . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET NOCAB='" . $nocab . "', BULAN='" . $month . "' " . $set;
                    $this->db->query($sql);
                    // $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE data20".$year.".".$ddl." FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='".$nocab."', BULAN='".$month."' ".$set;
                    echo "<pre>sql : ".$sql."</pre>";
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';

                    
                }
                
            } else {
            }
        }

        die;

        foreach ($ddl2 as $ddl) {
            $fields = $this->db->field_data("db_upload." . $ddl);
            $name = '(';
            $set = ',';
            $i = 1;
            foreach ($fields as $field) {
                if ($field->type == 'date') {
                    $name .= '@date' . $i . ',';
                    $set .= $field->name . '=' . 'str_to_date(@date' . $i . ',"%d-%m-%Y"),';
                    $i++;
                } else {
                    $name .= $field->name . ", ";
                }
            }
            $name = substr($name, 0, -2);
            $name .= ')';
            $set  = substr($set, 0, -1);
            $file = './assets/uploads/unzip/' . $nocab . '/' . strtoupper($ddl) . $nocab . '.TXT';
            if (is_file($file)) {
                if ($ddl == 'm_sales_salsman_ob') {
                    $sql_del = "delete from db_upload.$ddl where kode_comp = '$kode_comp' and nocab='$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . $year . $month . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET kode_comp= '$kode_comp',NOCAB='$nocab', BULAN='$month' " . $set;
                    $this->db->query($sql);
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';

                    // $sql_del_st_detail="delete from db_upload.st_detail where nocab='$nocab' and tanggal='$tanggal'";
                    // $this->db->query($sql_del_st_detail);
                    // $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.st_detail FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$year$month' ,TANGGAL='$tanggal' ".$set;    
                    // $this->db->query($sql);
                    // echo "<pre>";
                    // echo $sql_del."<br>";
                    // echo $sql."<br>";
                    // echo "</pre>";
                } elseif ($ddl == 'm_customer_creditlimit_prinsipal') {
                    $sql_del = "delete from db_upload.$ddl where kode_comp = '$kode_comp' and nocab='$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET kode_comp= '$kode_comp',NOCAB='$nocab' " . $set;
                    $this->db->query($sql);
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';

                    // $sql_del_st_detail="delete from db_upload.st_detail where nocab='$nocab' and tanggal='$tanggal'";
                    // $this->db->query($sql_del_st_detail);
                    // $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.st_detail FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$year$month' ,TANGGAL='$tanggal' ".$set;    
                    // $this->db->query($sql);
                    // echo "<pre>";
                    // echo $sql_del."<br>";
                    // echo $sql."<br>";
                    // echo "</pre>";
                } elseif ($ddl == 'm_setup_ppn') {
                    $sql_del = "delete from db_upload.$ddl where kode_comp = '$kode_comp' and nocab='$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET kode_comp= '$kode_comp',NOCAB='$nocab' " . $set;
                    $this->db->query($sql);
                    $msg[] = $ddl . $nocab . $year . $month . '.TXT' . ' found and uploaded <br />';

                    // $sql_del_st_detail="delete from db_upload.st_detail where nocab='$nocab' and tanggal='$tanggal'";
                    // $this->db->query($sql_del_st_detail);
                    // $sql = $load.strtoupper($ddl).$nocab.$year.$month.".TXT' INTO TABLE db_upload.st_detail FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' ".$name." SET NOCAB='$nocab', BULAN='$year$month' ,TANGGAL='$tanggal' ".$set;    
                    // $this->db->query($sql);
                    // echo "<pre>";
                    // echo $sql_del."<br>";
                    // echo $sql."<br>";
                    // echo "</pre>";
                } else {
                    $sql_del = "delete from db_upload.$ddl where nocab = '$nocab'";
                    $this->db->query($sql_del);
                    $sql = $load . strtoupper($ddl) . $nocab . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET NOCAB='$nocab' " . $set;
                    $proses = $this->db->query($sql);
                }
            } else {
            }
        }
        $sql_del = "delete from db_upload.tblang where nocab='$nocab' and kode_lang =''";
        $this->db->query($sql_del);
        foreach ($ddl3 as $ddl) {
            $fields = $this->db->field_data("db_upload." . $ddl);
            $name = '(';
            $set = ',';
            $i = 1;
            foreach ($fields as $field) {
                if ($field->type == 'date') {
                    $name .= '@date' . $i . ',';
                    $set .= $field->name . '=' . 'str_to_date(@date' . $i . ',"%d-%m-%Y"),';
                    $i++;
                } else {
                    $name .= $field->name . ", ";
                }
            }
            $name = substr($name, 0, -2);
            $name .= ')';
            $set  = substr($set, 0, -1);
            $file = './assets/uploads/unzip/' . $nocab . '/' . strtoupper($ddl) . '.TXT';
            if (is_file($file)) {
                $sql_del = "delete from db_upload.$ddl where nocab='$nocab'";
                $this->db->query($sql_del);
                $sql = $load . strtoupper($ddl) . ".TXT' INTO TABLE db_upload.$ddl FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' " . $name . " SET NOCAB='$nocab' " . $set;
                $this->db->query($sql);
            } else {
            }
        }

        $file = './assets/uploads/unzip/' . $nocab . '/TBPROD.TXT';
        if (is_file($file)) {
            $sql_del = "delete from db_upload.tabprod where nocab='$nocab'";
            $this->db->query($sql_del);
            $sql = $load . "TBPROD.TXT' INTO TABLE db_upload.tabprod FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB='$nocab'";
            $this->db->query($sql);
        } else {
        }
        $file = './assets/uploads/unzip/' . $nocab . '/TBRAYO~1.TXT';
        if (is_file($file)) {
            $sql_del = "delete from db_upload.tbrayon where nocab='$nocab'";
            $this->db->query($sql_del);
            $sql = $load . "TBRAYO~1.TXT' INTO TABLE db_upload.tbrayon FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\r\\n' SET NOCAB=''";
            $this->db->query($sql);
        } else {
            $msg[] = 'tbrayo~1.TXT' . ' not found<BR />';
        }

        // $sql_del = "
        //     delete from db_upload.fi 
        //     where nocab = '$nocab' and bulan = $month and kodeprod not in (select kodeprod from mpm.tabprod) ";
        // $this->db->query($sql_del);
        // $sql_del = "
        //     delete from db_upload.ri 
        //     where nocab = '$nocab' and bulan = $month and kodeprod not in (select kodeprod from mpm.tabprod) ";
        // $this->db->query($sql_del);

        // update tgl gto
        if ($kode_comp == 'GTO') {
            # code...
            $update = "
            update db_upload.fi a
            set a.TGLDOKJDI = REPLACE(a.tgldokjdi,'/','-') 
            where kode_comp ='$kode_comp'
            ";
            $this->db->query($update);
    
            $update_ri = "
            update db_upload.ri a
            set a.TGLDOKJDI = REPLACE(a.tgldokjdi,'/','-') 
            where kode_comp ='$kode_comp'
            ";
            $this->db->query($update_ri);
        }

        //update kolom status di tabel mpm.upload
        $data['lastupload'] = $lastupload;
        $x = $this->hitung_omzet3($data);
        $sql_madu_ri = "update mpm.upload set status='0', flag_check = '2' where lastupload='$lastupload'";
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
        } else {
            echo "ada kesalahan, silahkan ulangi";
            //redirect('proses_data/view_upload_all_dp','refresh');
        }
    }

    public function submitOmzet($data)
    {
        //$prosesData = $this->load->database('prosesData',TRUE);
        $nocab = $data['nocab'];
        $tahun = $data['tahun'];
        $tahun_stock = substr($tahun, 2, 2);
        $bulan = $data['bulan'];
        $tanggal = $data['tanggal'];
        $id = $data['id_upload'];
        $status_closing = $data['status_closing'];

        if ($status_closing == '') {
            $closing = $this->uri->segment('8');
        } else {
            $closing = $status_closing;
        }
        
        $check = $this->db->query("select flag_check from mpm.upload where id = $id order by id desc limit 1")->row_array();
        $flag_check = $check['flag_check'];

        $post['flag_check'] = '3';
        $this->db->where('id', $id);
        $this->db->update('mpm.upload', $post);

        if($flag_check != 3){

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
                delete from data$tahun.st_detail
                where bulan = $tahun_stock$bulan and nocab = '$nocab' and tanggal = $tanggal
            ";
            $proses_st_del = $this->db->query($sql);
            $sql = "
                insert into data$tahun.st_detail
                select * from db_upload.st_detail
                where bulan = $tahun_stock$bulan and nocab = '$nocab' and tanggal = $tanggal
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
            $sql = "
            delete from data$tahun.tbkota
            where nocab = '$nocab'
            ";
            $proses_tbkota_del = $this->db->query($sql);
            $sql = "
                insert into data$tahun.tbkota
                select * from db_upload.tbkota
                where nocab = '$nocab'
            ";
            $proses_tbkota = $this->db->query($sql);
            $sql = "
            delete from data$tahun.m_sales_salsman_ob
            where nocab = '$nocab'
            ";
            $proses_m_sales_del = $this->db->query($sql);
            $sql = "
                insert into data$tahun.m_sales_salsman_ob
                select * from db_upload.m_sales_salsman_ob
                where nocab = '$nocab'
            ";
            $proses_m_sales = $this->db->query($sql);
            $sql = "
            delete from data$tahun.m_customer_creditlimit_prinsipal
            where nocab = '$nocab'
            ";
            $proses_m_customer_del = $this->db->query($sql);
            $sql = "
                insert into data$tahun.m_customer_creditlimit_prinsipal
                select * from db_upload.m_customer_creditlimit_prinsipal
                where nocab = '$nocab'
            ";
            $proses_m_customer = $this->db->query($sql);

            $sql = "
            delete from data$tahun.m_setup_ppn
            where nocab = '$nocab'
            ";
            $proses_m_setup_ppn_del = $this->db->query($sql);
            $sql = "
                insert into data$tahun.m_setup_ppn
                select * from db_upload.m_setup_ppn
                where nocab = '$nocab'
            ";
            $proses_m_setup_ppn = $this->db->query($sql);
    
            // echo "<pre>";
            // if ($proses_fi_del) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_fi) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_ri_del) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_ri) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_fh_del) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_fh) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_rh_del) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_rh) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_st_del) {
            //     print_r($proses_st_del);
            //     print_r($proses_st);
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_st) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_tabsales_del) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_tabsales) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_tblang_del) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_tblang) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_tbrayon_del) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
    
            // if ($proses_tbrayon) {
            //     echo "1";
            // } else {
            //     echo "0";
            // }
            echo "</pre>";
            $x = $this->hitung_omzet4($id);
            $sql = "update mpm.upload set status='1', status_closing = $closing, omzet = " . '"' . $x . '"' . " ,flag_check = 4 where id='$id'";
            $proses = $this->db->query($sql);
            redirect("upload_file/alert_success/$id");

        }else{
            $message = "Data sedang di proses";
            echo "<script type='text/javascript'>alert('$message');
            window.location = 'http://site.muliaputramandiri.com/cisk/upload_file';
            </script>";
        }
    }
}
