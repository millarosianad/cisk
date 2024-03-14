<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_upload extends CI_Model 
{    
   public function cek_upload_terakhir($data_controller){
        //echo "ccccccccc";
        $id = $data_controller['id']."<br>";

        $this->db->order_by('id','desc');
        $this->db->select('');
        $this->db->where('upload.userid',$id);
        $hasil = $this->db->get('mpm.upload',1);

        $sql = "
            select *
            from mpm.upload a
            where a.userid = '$id' 
            ORDER BY id desc
            limit 1
        ";
        $hasil = $this->db->query($sql);

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
            // print_r($hasil->result());
        } else {
            return array();
        }   
   }

   public function cek_kesesuaian_upload($data_controller){
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
        echo "id login : ".$id_login."<br>";
        echo "filename_zip : ".$filename_zip."<br>";
        echo "tahun_zip : ".$tahun_zip."<br>";
        echo "bulan_zip : ".$bulan_zip."<br>";
        echo "tanggal_zip : ".$tanggal_zip."<br>";
        echo "status_closing_zip : ".$status_closing_zip."<br>";
        echo "nocab_zip : ".$nocab_zip."<br>";
        echo "</pre>";
        
        $data['filename'] = $filename_zip;
        $data['nocab'] = $nocab_zip;
        $data['year'] = $tahun_zip;
        $data['month'] = $bulan_zip;

        // $this->db->order_by('id','desc');
        // // $this->db->where('upload.flag <>','3');
        // $this->db->where('upload.tahun',$tahun_zip);
        // $this->db->where('upload.userid',$id_login);
        // $hasil = $this->db->get('mpm.upload',1);        

        // if ($hasil->num_rows() > 0) 
        // {
        //     foreach($hasil->result() as $row)
        //     {
        //         $filename_db = $row->filename;
        //         $lastupload_db = $row->lastupload;
        //         $tanggal_db = $row->tanggal;
        //         $bulan_db = $row->bulan;
        //         $tahun_db = $row->tahun;
        //         $status_closing_db = $row->status_closing;
        //         $status = $row->status;
        //     }
        // } else {
        //     //return array();
        //     $filename_db = "";
        //     $lastupload_db = "";
        //     $tanggal_db = "";
        //     $bulan_db = "0";
        //     $tahun_db = $tahun_zip;
        //     $status_closing_db = "0";
        // }
        
        // echo "<pre>";
        // echo "filename_db : ".$filename_db."<br>";
        // echo "lastupload_db : ".$lastupload_db."<br>";
        // echo "tanggal_db : ".$tanggal_db."<br>";
        // echo "bulan_db : ".$bulan_db."<br>";
        // echo "tahun_db : ".$tahun_db."<br>";
        // echo "closing db :" .$status_closing_db."<br>";
        // echo "status :" .$status."<br>";        
        // echo "</pre>";

        echo "<pre>";
        $x = $data_controller['query'];
        foreach($x as $last_upload){
            $tahun_db = $last_upload->tahun;
            $bulan_db = $last_upload->bulan;
            $status_closing_db = $last_upload->status_closing;
            $status = $last_upload->status;
            $tanggal_db = $last_upload->tanggal;
        }
        // echo "tahun : ".$tahun_db;
        echo "</pre>";
        
        if ($tahun_db == $tahun_zip) {
            if ($bulan_db == $bulan_zip) 
            {
                if ($status_closing_db == 1 && $status == 1) {
                    echo '<script>alert("Upload gagal. Bulan ini sudah anda closing saat upload sebelumnya. Silahkan hubungi IT untuk membuka kunci bulan lalu");</script>';
                }else{
                    if ($tanggal_db > $tanggal_zip) {
                        echo '<script>alert("Upload gagal. Silahkan upload omzet yang mempunyai tanggal lebih besar !");</script>';
                    }else{
                        redirect('all_upload/proses_extract_zip/'.$filename_zip.'/'.$nocab_zip.'/'.$tahun_zip.'/'.$bulan_zip.'/'.$tanggal_zip.'/'.$status_closing_zip);
                    }
                }
            // }elseif($bulan_db > $bulan_zip){
            //     echo '<script>alert("Upload gagal. Anda tidak dapat meng-Upload data bulan lalu !");</script>';
            }else{
                //harus cek apakah sudah closing bulan sebelumnya
                if ($status_closing_db == 1 && $status == 1) {
                    redirect('all_upload/proses_extract_zip/'.$filename_zip.'/'.$nocab_zip.'/'.$tahun_zip.'/'.$bulan_zip.'/'.$tanggal_zip.'/'.$status_closing_zip);
                }else{
                    echo '<script>alert("Upload gagal. Silahkan UPLOAD dan SUBMIT data CLOSING dan bulan lalu terlebih dahulu !");</script>';
                }
            }
        }elseif ($tahun_db > $tahun_zip) {
            echo '<script>alert("Upload gagal. Anda tidak dapat meng-Upload data tahun lalu !");</script>';
        }else{
            if ($status_closing_db == 1) {
                redirect('all_upload/proses_extract_zip/'.$filename_zip.'/'.$nocab_zip.'/'.$tahun_zip.'/'.$bulan_zip.'/'.$tanggal_zip.'/'.$statusClosing_zip);
            }else{
                echo '<script>alert("Upload gagal. Silahkan upload data closing bulan lalu terlebih dahulu !");</script>';
            }
        }
        
        // if($tahun_zip == $tahun_db && ($bulan_zip == $bulan_db || $bulan_db == NULL))
        // {
        //     if ($status_closing_db == 1)
        //     {
        //         echo '<script>alert("Upload gagal. Bulan ini sudah anda closing saat upload sebelumnya");</script>';
        //     }
        //     else{
        //         if ($tanggal_zip >= $tanggal_db) {
        //             //echo '<script>alert("ok");</script>';
        //             echo "Zzzzzzzz";
        //             //redirect('all_upload/proses_extract_zip/'.$filename_zip.'/'.$nocab_zip.'/'.$tahun_zip.'/'.$bulan_zip.'/'.$tanggal_zip.'/'.$status_closing_zip);
                     

        //         }else{
        //             echo '<script>alert("Upload gagal. Zip yang anda upload mempunyai tanggal yang lebih kecil dari data upload sebelumnya !");</script>';
                    
        //         }
        //     }
        // }elseif ($tahun_zip == $tahun_db && $bulan_zip >= $bulan_db) {
        //     if ($status_closing_db == 1)
        //     {
        //         echo '<script>alert("Upload gagal. Bulan ini sudah anda closing saat upload sebelumnya");</script>';
        //     }
        //     else
        //     {
        //         //redirect('all_upload/proses_extract_zip/'.$filename_zip.'/'.$nocab_zip.'/'.$tahun_zip.'/'.$bulan_zip.'/'.$tanggal_zip.'/'.$status_closing_zip);
        //     }
        // }

        // else{
        //     echo '<script>alert("Upload gagal. Ada kesalahan dalam proses upload. Mohon hubungi IT MPM !");</script>';
        // }
        

   }

}