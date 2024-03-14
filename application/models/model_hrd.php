<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_hrd extends CI_Model 
{    
    public function proses_input_karyawan($dataSegment){

        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');   
        $id=$this->session->userdata('id');

        /* cek nik sudah ada di tabel atau belum */
        $sql = "
            select * from hrd.tbl_kary
            where nik = ".'"'.$dataSegment['nik'].'"'."
        ";
        
        $hasil = $this->db->query($sql);
        /*
        echo "<pre>";
        print_r($sql);
        print_r($hasil);
        echo "</pre>";
        */

        if ($hasil->num_rows >= '1') {
            
            echo '<script>alert("Tambah Karyawan Gagal. Nik ini sudah terdaftar sebelumnya");</script>';
            echo "<a href =input_karyawan>kembali</a>";
        }else{

            /*konversi format tanggal*/
            
            $tgl_lahir_x=trim($dataSegment['tgl_lahir']);//$dob1='dd/mm/yyyy' format
            $tgl_lahir_y=strftime('%Y-%m-%d',strtotime($tgl_lahir_x));

            $tgl_gabung_x=trim($dataSegment['tgl_gabung']);//$dob1='dd/mm/yyyy' format
            $tgl_gabung_y=strftime('%Y-%m-%d',strtotime($tgl_gabung_x));

            $tgl_resign_x=trim($dataSegment['tgl_resign']);//$dob1='dd/mm/yyyy' format
            $tgl_resign_y=strftime('%Y-%m-%d',strtotime($tgl_resign_x));
            if ($tgl_resign_y = "1970-01-01") {
                $tgl_resign_y = '';
            }else{
                $tgl_resign_y = $tgl_resign_y;
            }

            //query insert data ke tabel mpm.tbl_complain_system            

            $data = array(
                   'nik'            => $dataSegment['nik'],
                   'nama_kary'      => $dataSegment['nama'],
                   'tgl_lahir'      => $tgl_lahir_y,
                   'alamat'         => $dataSegment['alamat'],
                   'email'          => $dataSegment['email'],
                   'kontak'         => $dataSegment['kontak'],
                   'divisi'         => $dataSegment['divisi'],
                   'jabatan'        => $dataSegment['jabatan'],
                   'tgl_gabung'     => $tgl_gabung_y,
                   'status'         => $dataSegment['status'],
                   'tgl_resign'     => $tgl_resign_y,
                   'created'        => date('Y-m-d H:i:s'),
                   'created_by'     => $id,
                   'foto'           => $dataSegment['foto']
                );

            $proses = $this->db->insert('hrd.tbl_kary',$data);

            if ($proses == '1') 
            {
                echo "insert success";
                //$this->email_input($data);
                redirect('all_hrd/view_karyawan','refresh');
            }
            else
            {
                echo "gagal";
                //return array(); 
            }
            
        }
    }

    public function proses_input_divisi($dataSegment){

        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');   
        $id=$this->session->userdata('id');

        //query insert data ke tabel mpm.tbl_complain_system            

        $data = array(
               'kode_divisi'    => $dataSegment['kode_divisi'],
               'nama_divisi'    => $dataSegment['nama_divisi'],
               'created'        => date('Y-m-d H:i:s'),
               'created_by'     => $id,
            );

        $proses = $this->db->insert('hrd.tbl_divisi',$data);

        if ($proses == '1') 
        {
            echo "insert success";
            //$this->email_input($data);
            redirect('all_hrd/input_divisi','refresh');
        }
        else
        {
            echo "gagal";
            //return array(); 
        }
        
    }

    public function proses_input_hak($dataSegment){

        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');   
        $id=$this->session->userdata('id');

        //query insert data ke tabel mpm.tbl_complain_system            



        $data = array(
               'nik'    => $dataSegment['nik'],
               'tahun'    => $dataSegment['tahun'],
               'hak_cuti'    => $dataSegment['hak_cuti'],
               'jenis_cuti'    => $dataSegment['jenis_cuti'],
               'created'        => date('Y-m-d H:i:s'),
               'created_by'     => $id,
            );

        $proses = $this->db->insert('hrd.tbl_hak_cuti',$data);

        if ($proses == '1') 
        {
            echo "insert success";
            //$this->email_input($data);
            redirect('all_hrd/input_hak_cuti','refresh');
        }
        else
        {
            echo "gagal";
            //return array(); 
        }
        
    }

    public function proses_input_cuti($dataSegment){

        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');   
        $id=$this->session->userdata('id');

        /*konversi format tanggal*/
        $tgl_cuti_x=trim($dataSegment['tgl_cuti']);//$dob1='dd/mm/yyyy' format
        $tgl_cuti_y=strftime('%Y-%m-%d',strtotime($tgl_cuti_x)); 

        $tahun = strftime('%Y',strtotime($tgl_cuti_x)); 
             

        $data = array(
               'nik'        => $dataSegment['nik'],
               'bulan'      => $dataSegment['bulan'],
               'jenis_cuti' => $dataSegment['jenis_cuti'],
               'tahun'      => $tahun,
               'tgl_cuti'   => $tgl_cuti_y,
               'status_potong' => $dataSegment['status'],
               'keterangan' => $dataSegment['keterangan'],
               'created'    => date('Y-m-d H:i:s'),
               'created_by' => $id,               
            );

        $proses = $this->db->insert('hrd.tbl_cuti_detail',$data);

        if ($proses == '1') 
        {
            echo "insert success";
            //$this->email_input($data);
            redirect('all_hrd/view_cuti','refresh');
        }
        else
        {
            echo "gagal";
            //return array(); 
        }
        
    }

    public function get_divisi()
    {
        $sql='select id,nama_divisi from hrd.tbl_divisi';
        return $this->db->query($sql);
    }

    public function get_divisi_edit($dataSegment)
    {
        $sql="
            select id,nama_divisi from hrd.tbl_divisi
            ORDER BY id = (select divisi from hrd.tbl_kary
            where id = ".$dataSegment['id_kary']."
            )desc
        ";
        return $this->db->query($sql);
    }

    public function get_status_edit($dataSegment)
    {
        $sql="
            select id,nama_status
            from hrd.tbl_status
            ORDER BY id = (
                select `status` 
                from hrd.tbl_kary
                where id = ".$dataSegment['id_kary']."
            )desc
        ";
        return $this->db->query($sql);
    }

    public function get_karyawan()
    {
        $sql='
            select nik, nama_kary, nama_divisi 
            from hrd.tbl_kary a LEFT JOIN hrd.tbl_divisi b
                on a.divisi = b.id';
        return $this->db->query($sql);
    }

    public function get_jenis_cuti()
    {
        $sql='
            select * from hrd.tbl_jenis_cuti 
            ';
        return $this->db->query($sql);
    }

    public function proses_edit_karyawan($datasegment){


        $dob1=trim($datasegment['tgl_lahir']);//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));

        echo "tgl_lahir : ".$dob_disp1;

        $dob2=trim($datasegment['tgl_resign']);//$dob1='dd/mm/yyyy' format
        $dob_disp2=strftime('%Y-%m-%d',strtotime($dob2));

        echo "tgl_resign : ".$dob_disp2;
    
        $data = array(
                'id'            => $dataasset['id'],
                'nama_it'       => $dataasset['nama_it'],
                'id_status'     => $dataasset['id_status'],
                'tgl_selesai'   => $dob_disp1,
                'solusi'        => $dataasset['solusi'],
                'file_it'       => $dataasset['image_it'],
                'note_tambahan' => $dataasset['note_tambahan']
            );
        /*
        echo "<br>id : ".$dataasset['id']."<br>";
        echo "nama_it : ".$dataasset['nama_it']."<br>";
        echo "id_status : ".$dataasset['id_status']."<br>";
        echo "tgl_selesai : ".$dob_disp1."<br>";
        echo "solusi : ".$dataasset['solusi']."<br>";
        echo "file it : ".$dataasset['image_it']."<br>";        
        echo "idnyax : ".$data['id'];
        */
        //Query update
        $hasil = $this->db->where('id',$dataasset['id'])
                 ->update('mpm.tbl_complain_system', $data);
        
        //print_r($hasil);

        if ($hasil == '1') 
            {
                //echo "update berhasil";
                $this->email_edit($data);
                //redirect('all_help/view_complain');
            } else {
                return array();
            }
        
    }

    public function getFlag()
    {
        //$sql='select id,nama from mpm.tbl_flag';
        //return $this->db->query($sql);

        $id = $this->uri->segment(3);

        if ($id != NULL) {
            
            $sql= "
            select  a.id, a.nama_status
            from    mpm.tbl_status_complain a
                        LEFT JOIN mpm.tbl_complain_system b
                            on a.id = b.id_status
            ORDER BY b.id = $id desc
            ";

        } else {
            
            $sql= "
            select  a.id, a.nama_status
            from    mpm.tbl_status_complain a
                        LEFT JOIN mpm.tbl_complain_system b
                            on a.id = b.id_status
            ORDER BY a.id asc
            ";

        }       
        /*
        echo "<pre><br><br>";
        print_r($sql);
        echo "</pre>";
        */
        return $this->db->query($sql);

    }

    public function view_karyawan()
    {
        $id=$this->session->userdata('id');
        $this->db->order_by("tbl_kary.id", "desc");
        $this->db->join('hrd.tbl_divisi', 'tbl_divisi.id = tbl_kary.divisi','left');
        $this->db->select('tbl_kary.id,tbl_kary.nama_kary,tbl_kary.nik,tbl_kary.tgl_lahir,tbl_kary.kontak,tbl_divisi.nama_divisi');
        $hasil = $this->db->get('hrd.tbl_kary');

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function view_cuti()
    {
        $id=$this->session->userdata('id');
        
        /*
        $sql = "
            select * from hrd.tbl_cuti a
            LEFT JOIN hrd.tbl_kary b
                on a.nik = b.nik
            LEFT JOIN hrd.tbl_divisi c
                on c.id = b.divisi
            order by a.tahun desc, b.nama_kary asc
        ";
        */

        $sql = "
            select id, nik, nama_kary, nama_divisi, a.tahun, b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,total_cuti,hak_cuti,sum(hak_cuti-total_cuti) as sisa
            FROM
            (
                SELECT  a.id, a.nik, b.nama_kary, a.tahun, d.nama_divisi,
                                SUM(if(bulan = 1,total,0)) as b1,SUM(if(bulan = 2,total,0)) as b2,
                                SUM(if(bulan = 3,total,0)) as b3,SUM(if(bulan = 4,total,0)) as b4,
                                SUM(if(bulan = 5,total,0)) as b5,SUM(if(bulan = 6,total,0)) as b6, 
                                SUM(if(bulan = 7,total,0)) as b7,SUM(if(bulan = 8,total,0)) as b8,
                                SUM(if(bulan = 9,total,0)) as b9,SUM(if(bulan = 10,total,0)) as b10,
                                SUM(if(bulan = 11,total,0)) as b11,SUM(if(bulan = 12,total,0)) as b12, 
                                sum(total) as total_cuti, c.hak_cuti
                FROM
                (
                        select id, nik, tahun, bulan, count(*) as total
                        from hrd.tbl_cuti_detail
                        where status_potong = 1
                        GROUP BY nik,tahun,bulan
                )a  LEFT JOIN hrd.tbl_kary b
                            on a.nik = b.nik
                        LEFT JOIN
                        (
                                select * from hrd.tbl_hak_cuti
                                where jenis_cuti = 1
                        )c on a.nik = c.nik
                        LEFT JOIN hrd.tbl_divisi d
                            on d.id = b.divisi
                        GROUP BY nik
            )a GROUP BY a.nik
        ";

        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function view_divisi()
    {
        $hasil = $this->db->get('hrd.tbl_divisi');

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function view_hak()
    {
        
        $sql = "
            select a.id,a.nik,b.nama_kary,a.hak_cuti,a.tahun,a.jenis_cuti, c.nama_divisi
            from hrd.tbl_hak_cuti a 
                LEFT JOIN hrd.tbl_kary b
                    on a.nik = b.nik
                LEFT JOIN hrd.tbl_divisi c
                    on b.divisi = c.id
        ";

        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function view_jenis_cuti()
    {
        $hasil = $this->db->get('hrd.tbl_jenis_cuti');

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function detail_kary()
    {
        $id = $this->uri->segment(3);
        $sql = "
            select  a.nik,a.nama_kary,a.tgl_lahir,b.nama_divisi,
                    a.jabatan,
                    a.email,a.tgl_gabung,a.tgl_resign,a.foto,a.kontak,
                    a.alamat,a.`status`,a.created,a.created_by
            FROM    hrd.tbl_kary a LEFT JOIN 
                    hrd.tbl_divisi b
                        on a.divisi = b.id
            where   a.id = $id
        ";

        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function detail_complain(){

        $id = $this->uri->segment(3);

        //echo br(10);

        //echo "id : ".$id;
        $this->db->where("tbl_complain_system.id = ".$id);
        $this->db->join('user', 'tbl_complain_system.userid_pelapor = user.id','left');
        $this->db->join('mpm.tbl_status_complain', 'tbl_status_complain.id = tbl_complain_system.id_status','left');
        $this->db->join('mpm.tbl_kategori_complain', 'tbl_kategori_complain.id = tbl_complain_system.id_kategori','left');
        $this->db->select('tbl_complain_system.id as id_tiket, tbl_complain_system.userid_pelapor, tbl_complain_system.nama_pelapor, tbl_complain_system.email_pelapor, tbl_kategori_complain.id, tbl_kategori_complain.nama_kategori, tbl_status_complain.id, tbl_status_complain.nama_status, tbl_complain_system.masalah, tbl_complain_system.file, tbl_complain_system.user_it, tbl_complain_system.nama_it, tbl_complain_system.solusi, tbl_complain_system.tgl_selesai, tbl_complain_system.id_status, tbl_complain_system.tgl_ajuan, tbl_complain_system.id_kategori, tbl_complain_system.kontak_pelapor,user.username,tbl_complain_system.file_it, tbl_complain_system.note_tambahan');
        $hasil = $this->db->get('mpm.tbl_complain_system');
        
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }

    }

    public function proses_update($dataasset){

        //echo "tglperol : ".$dataasset['tglperol']."<br>";

        $dob1=trim($dataasset['tgl_selesai']);//$dob1='dd/mm/yyyy' format
        $dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));

        //echo "tgl_selesai : ".$dob_disp1;
    
        $data = array(
                'id'            => $dataasset['id'],
                'nama_it'       => $dataasset['nama_it'],
                'id_status'     => $dataasset['id_status'],
                'tgl_selesai'   => $dob_disp1,
                'solusi'        => $dataasset['solusi'],
                'file_it'       => $dataasset['image_it'],
                'note_tambahan' => $dataasset['note_tambahan']
            );
        /*
        echo "<br>id : ".$dataasset['id']."<br>";
        echo "nama_it : ".$dataasset['nama_it']."<br>";
        echo "id_status : ".$dataasset['id_status']."<br>";
        echo "tgl_selesai : ".$dob_disp1."<br>";
        echo "solusi : ".$dataasset['solusi']."<br>";
        echo "file it : ".$dataasset['image_it']."<br>";        
        echo "idnyax : ".$data['id'];
        */
        //Query update
        $hasil = $this->db->where('id',$dataasset['id'])
                 ->update('mpm.tbl_complain_system', $data);
        
        //print_r($hasil);

        if ($hasil == '1') 
            {
                //echo "update berhasil";
                $this->email_edit($data);
                //redirect('all_help/view_complain');
            } else {
                return array();
            }
        
    }

    public function proses_delete($id){
        
        $this->db->where('id', $id)
                 ->delete('mpm.tbl_complain_system');
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
         $config['mailtype'] = 'html';

         $this->email->initialize($config);
    }

    public function email_input($data){
        
        /* cari nama kategori */
            $this->db->where('id = '.'"'.$data['id_kategori'].'"');
            $query = $this->db->get('mpm.tbl_kategori_complain');
            foreach ($query->result() as $row) {
                $nama_kategori = $row->nama_kategori;
                //echo "nama_kategori : ".$nama_kategori."<br>";
        }
        /* end cari nama kategori */

        /* cari nama status */
            $this->db->where('id = '.'"'.$data['id_status'].'"');
            $query = $this->db->get('mpm.tbl_status_complain');
            foreach ($query->result() as $row) {
                $nama_status = $row->nama_status;
                //echo "nama_kategori : ".$nama_status."<br>";
        }
        /* end cari nama kategori */


        $this->email_config();

        /* jika tidak ada attachment */

        if ($data['file'] == "tidak ada") {
            
        } else {
            $this->email->attach('uploads/'.$data['file'].'');
        }

        /* end jika tidak ada attachment */

        //$this->email->attach('uploads/'.$data['image'].'');
        
        /*
        echo "Userid pelapor : ".$data['userid_pelapor']."<br>";
        echo "Nama pelapor : ".$data['nama_pelapor']."<br>";
        echo "Email pelapor : ".$data['email_pelapor']."<br>";        
        echo "Kontak pelapor : ".$data['kontak_pelapor']."<br>";
        echo "Tgl ajuan : ".$data['tgl_ajuan']."<br>";
        echo "Kategori : ".$nama_kategori."<br>";
        echo "Permasalahan : ".$data['masalah']."<br>";
        echo "Status : ".$nama_status."<br>";
        echo "File : ".$data['file']."<br>";
        */
        $isi = "

        Dear Bpk / Ibu ".$data['nama_pelapor'].",<br><br>

        Berikut adalah detail informasi yang telah anda masukkan ke dalam menu Complaine System :
        <br><br>
        <i><h3>Data Pelapor</h3></i><hr>
        User ID                 : ".$data['userid_pelapor']." \r\n <br>
        Nama                    : ".$data['nama_pelapor']." \r\n <br>
        Email                   : ".$data['email_pelapor']." \r\n <br>
        kontak                  : ".$data['kontak_pelapor']." \r\n <br>
        Tgl pengajuan           : ".$data['tgl_ajuan']." \r\n <br>
        Kategori permasalahan   : ".$nama_kategori." \r\n <br>
        Detail permasalahan     : ".$data['masalah']." \r\n <br>
        status permasalahan     : ".$nama_status." \r\n <br>
        Image / Lampiran        : ".$data['file']." \r\n \r\n <hr><br>

        (*) Email ini akan otomatis terkirim ke IT MPM, mohon tunggu informasi selanjutnya. \r\n \r\n <br><br>

        Terima kasih<br>
        ";

        //print_r($isi);

        $this->email->to($data['email_pelapor']);
        //isi dengan email IT
        $this->email->cc('suffy.yanuar@gmail.com,ninol_cute@yahoo.com,hendyfaturahman@gmail.com,yanahyani@yahoo.com');
        
        $this->email->from('muliaputramandiri@gmail.com','Automatic Email - MPM Site');
        $this->email->subject('Informasi Penginputan - Complaine System');
        $this->email->message($isi);
        //$this->email->attach('LOKASI_FOLDER_FILE/NAMA_FILE_attachment.pdf');
        $this->email->send();
        //echo $this->email->print_debugger();
        redirect('all_help/view_complain_user','refresh');
    }

    public function email_edit($data){
        /*
        $data = array(
                'id'            => $dataasset['id'],
                'nama_it'       => $dataasset['nama_it'],
                'flag_selesai'  => $dataasset['flag_selesai'],
                'tgl_selesai'   => $dob_disp1,
                'solusi'        => $dataasset['solusi']
            );
        */
        /* cari nama kategori */
            $this->db->where('tbl_complain_system.id = '.'"'.$data['id'].'"');
            $this->db->join('mpm.tbl_kategori_complain','mpm.tbl_kategori_complain.id = mpm.tbl_complain_system.id_kategori','left');
            $this->db->select('tbl_complain_system.id,tbl_complain_system.id_kategori,tbl_kategori_complain.id,tbl_kategori_complain.nama_kategori,tbl_complain_system.file_it');
            $query = $this->db->get('mpm.tbl_complain_system');
            foreach ($query->result() as $row) {
                $nama_kategori = $row->nama_kategori;
                //echo "nama_kategori : ".$nama_kategori."<br>";
        }
        /* end cari nama kategori */

        /* cari nama image */
            $this->db->where('id = '.'"'.$data['id'].'"');
            $query = $this->db->get('mpm.tbl_complain_system');
            foreach ($query->result() as $row) {
                $image = $row->file;
                $nama_pelapor = $row->nama_pelapor;
                $userid_pelapor = $row->userid_pelapor;
                $email_pelapor = $row->email_pelapor;
                $kontak_pelapor = $row->kontak_pelapor;
                $tgl_ajuan = $row->tgl_ajuan;
                $masalah = $row->masalah;
                $nama_it = $row->nama_it;
                $solusi = $row->solusi;
                $tgl_selesai = $row->tgl_selesai;
                $file_it = $row->file_it;
                //echo "nama_kategori : ".$nama_status."<br>";

                if ($tgl_selesai == "1970-01-01") {
                     $tgl_selesai = "belum ditentukan";
                } else {
                    $tgl_selesai = $row->tgl_selesai;
                }
                
        }
        /* end cari nama kategori */

        /* cari nama status */
            $this->db->where('id = '.'"'.$data['id_status'].'"');
            $query = $this->db->get('mpm.tbl_status_complain');
            foreach ($query->result() as $row) {
                $nama_status = $row->nama_status;

                //echo "nama_kategori : ".$nama_status."<br>";
        }
        /* end cari nama kategori */


        $this->email_config();
        /* jika tidak ada attachment */

        if ($image == "tidak ada") {
            //$this->email->attach('uploads/it/'.$data['file_it'].'');
            //echo "xxx : ".$data['file_it'];
        } else {
            $this->email->attach('uploads/'.$data['image'].'');
            //$this->email->attach('uploads/it/'.$data['file_it'].'');
            //echo "xxx : ".$data['file_it'];
        }

        /* end jika tidak ada attachment */
        
        echo "id tiket : ".$data['id']."<br>";
        echo "userid pelapor : ".$userid_pelapor."<br>";
        echo "nama pelapor : ".$nama_pelapor."<br>";
        echo "email pelapor : ".$email_pelapor."<br>";        
        echo "kontak pelapor : ".$kontak_pelapor."<br>";
        echo "tgl ajuan : ".$tgl_ajuan."<br>";
        echo "kategori : ".$nama_kategori."<br>";
        echo "permasalahan : ".$masalah."<br>";
        echo "status : ".$nama_status."<br>";
        echo "image : ".$image."<br>";
        
        $isi = "

        Dear Bpk / Ibu ".$nama_pelapor.",<br><br>

        Berikut adalah Update Informasi yang berasal dari menu Complaine System :
        <br><br>
        <i><h3>Data Pelapor</h3></i><hr>
        Nama                    : ".$nama_pelapor." \r\n <br>
        Email                   : ".$email_pelapor." \r\n <br>
        kontak                  : ".$kontak_pelapor." \r\n <br>
        Tgl pengajuan           : ".$tgl_ajuan." \r\n <br>
        Kategori permasalahan   : ".$nama_kategori." \r\n <br>
        Detail permasalahan     : ".$masalah." \r\n <br>
        status permasalahan     : ".$nama_status." \r\n <br>
        Image / Lampiran        : ".$image." \r\n \r\n <hr><br>

        <i><h3>Data IT</h3></i><hr>
        Nama                    : ".$nama_it." \r\n <br>
        solusi                  : ".$solusi." \r\n <br>
        tanggal selesai         : ".$tgl_selesai." \r\n <br>

        (*) Email ini akan otomatis terkirim ke IT MPM, mohon tunggu informasi selanjutnya. \r\n \r\n <br><br>

        Terima kasih<br>
        ";

        $this->email->to($email_pelapor);
        $this->email->cc('suffy.yanuar@gmail.com,ninol_cute@yahoo.com,hendyfaturahman@gmail.com,yanahyani@yahoo.com');
        //$this->email->to("suffy.yanuar@gmail.com");
        //$this->email->cc('suffy.yanuar@gmail.com');
        $this->email->from('muliaputramandiri@gmail.com','Automatic Email - MPM Site');
        $this->email->subject('Informasi Penginputan - Complaine System');
        $this->email->message($isi);
        //$this->email->attach('LOKASI_FOLDER_FILE/NAMA_FILE_attachment.pdf');
        $this->email->send();
        //echo $this->email->print_debugger();

        redirect('all_help/view_complain','refresh');

    }

}