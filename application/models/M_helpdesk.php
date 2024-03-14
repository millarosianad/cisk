<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_helpdesk extends CI_Model
{
    public function get_sitecode()
    {
        $id = $this->session->userdata('id');
        // echo "id : ".$id;
        $year = date('Y');
        if($id == 547 || $id == 297 || $id == 588 || $id == 857 || $id == 580){
            $sql = "
            SELECT a.id, a.username, a.company, CONCAT(b.kode_comp,b.nocab) as site_code, b.nama_comp
            FROM
            (
                SELECT *
                FROM mpm.`user`
            )a LEFT JOIN
            (
                SELECT a.*
                FROM
                (
                SELECT CONCAT(kode_comp, nocab) as kode, KODE_COMP, NOCAB, nama_comp
                FROM mpm.tbl_tabcomp
                where `status` = 1
                )a INNER JOIN
                (
                SELECT CONCAT(kode_comp, nocab) as kode
                FROM db_dp.t_dp
                WHERE tahun = $year AND `status` = 1
                )b on a.kode = b.kode
            )b on a.username = b.kode_comp
            order by b.nama_comp";
        }
        else{
            // $sql = "
            // SELECT a.id, a.username, a.company, CONCAT(b.kode_comp,b.nocab) as site_code, b.nama_comp
            // FROM
            // (
            //     SELECT *
            //     FROM mpm.`user`
            //     WHERE id = $id
            // )a LEFT JOIN
            // (
            //     SELECT a.*
            //     FROM
            //     (
            //     SELECT CONCAT(kode_comp, nocab) as kode, KODE_COMP, NOCAB, nama_comp
            //     FROM mpm.tbl_tabcomp
            //     where `status` = 1
            //     )a INNER JOIN
            //     (
            //     SELECT CONCAT(kode_comp, nocab) as kode
            //     FROM db_dp.t_dp
            //     WHERE tahun = $year AND `status` = 1
            //     )b on a.kode = b.kode
            // )b on a.username = b.kode_comp
            // order by b.nama_comp";

            $sql = "
            SELECT  a.id, a.username, a.company, 
                    if(CONCAT(b.kode_comp,b.nocab) is null, a.username, CONCAT(b.kode_comp,b.nocab))  as site_code, 
                    if(b.nama_comp is null, a.company, b.nama_comp) as nama_comp
            FROM
            (
                SELECT *
                FROM mpm.`user`
                WHERE id = $id
            )a LEFT JOIN
            (
                SELECT a.*
                FROM
                (
                    SELECT CONCAT(kode_comp, nocab) as kode, KODE_COMP, NOCAB, nama_comp
                    FROM mpm.tbl_tabcomp
                    where `status` = 1
                )a INNER JOIN
                (
                    SELECT CONCAT(kode_comp, nocab) as kode
                    FROM db_dp.t_dp
                    WHERE tahun = $year AND `status` = 1
                )b on a.kode = b.kode
            )b on a.username = b.kode_comp
            order by b.nama_comp
            ";
        }
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_helpdesk_by_id($id)
    {
        $sql = "
            SELECT * from db_temp.t_temp_helpdesk a
            where a.id= '$id'
            LIMIT 1";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_helpdesk_by_tiket($tiket_email)
    {
        $sql = "
            SELECT * from db_temp.t_temp_helpdesk a
            INNER JOIN db_temp.t_temp_helpdesk_detail b
            on a.id_tiket = b.id_tiket
            where md5(a.id_tiket) = '$tiket_email'
            order by b.created_date DESC
            LIMIT 1";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_email_dp($tiket)
    {
        $sql = "
            select email from mpm.user
            where username = '$tiket'
        ";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function add_helpdesk($post)
    {
        $id = $this->session->userdata('id');

        $site_code = $post['site_code'];
        $tgl = date("Y-m-d",strtotime($post['created_date']));
        // var_dump($tgl);die;

        $this->db->select('RIGHT(db_temp.t_temp_helpdesk.id_tiket,5) as tiket', FALSE);
        $this->db->where('site_code',$site_code);
        $this->db->order_by('id','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('db_temp.t_temp_helpdesk');
            if($query->num_rows() <> 0){      
                $data = $query->row();
                $kode = intval($data->tiket) + 1; 
            }
            else{      
                $kode = 1;  
            }
        $kodetiket = str_pad($kode, 5, "0", STR_PAD_LEFT);    
        $tiket = "HLP/$site_code/$tgl/$kode";
        $tiket_email = md5($tiket);
        // var_dump($tiket_email);die;

        $data = [
            'id_tiket' => $tiket,
            'site_code' => $site_code,
            'name' => $post['name'],
            'telp' => $post['telp'],
            'email' => $post['email'],
            'id_kategori' => $post['masalah'],
            'status' => 'belum diproses',
            'created_by' => $id,
            'created_date' => $post['created_date'],
            'updated_by' => '',
            'last_updated' => $post['created_date'],
        ];

        $this->db->insert('db_temp.t_temp_helpdesk', $data);
        
        $data2 = [
            'id_tiket' => $tiket,
            'username' => $this->session->userdata('username'),
            'note' => $post['deskripsi'],
            'filename1' => $post['filename1'],
            'filename2' => $post['filename2'],
            'filename3' => $post['filename3'],
            'video' => $post['video'],
            'created_by' => $id,
            'created_date' => $post['created_date'],
            'updated_by' => '',
            'last_updated' => $post['created_date'],
        ];
        $this->db->insert('db_temp.t_temp_helpdesk_detail', $data2);
        
        redirect("helpdesk/email_helpdesk/$tiket_email");
    }

    public function history_helpdesk()
    {
        $id = $this->session->userdata('id');
        if($id == 547 || $id == 297 || $id == 588 || $id == 857){
            $sql="
                SELECT b.*
                FROM
                (
                    SELECT a.id, a.username, a.company, CONCAT(b.kode_comp,b.nocab) as site_code, b.nama_comp
                    FROM
                    (
                        SELECT *
                        FROM mpm.`user`
                    )a LEFT JOIN
                    (
                        SELECT a.*
                        FROM
                        (
                        SELECT KODE_COMP, NOCAB, nama_comp
                        FROM mpm.tbl_tabcomp
                        )a INNER JOIN
                        (
                        SELECT kode_comp
                        FROM db_dp.t_dp
                        WHERE tahun = 2023 AND `status` = 1
                        )b on a.kode_comp = b.kode_comp
                    )b on a.username = b.kode_comp
                )a
                INNER JOIN 
                (
                SELECT * FROM db_temp.t_temp_helpdesk
                )b on a.site_code = b.site_code
                group by b.id_tiket
                order by b.created_date desc
            ";
        }else{
            $sql="
                SELECT b.*
                FROM
                (
                SELECT a.id, a.username, a.company, CONCAT(b.kode_comp,b.nocab) as site_code, b.nama_comp
                FROM
                (
                    SELECT *
                    FROM mpm.`user`
                    WHERE id = $id
                )a LEFT JOIN
                (
                    SELECT a.*
                    FROM
                    (
                    SELECT KODE_COMP, NOCAB, nama_comp
                    FROM mpm.tbl_tabcomp
                    )a INNER JOIN
                    (
                    SELECT kode_comp
                    FROM db_dp.t_dp
                    WHERE tahun = 2023 AND `status` = 1
                    )b on a.kode_comp = b.kode_comp
                )b on a.username = b.kode_comp
                )a
                INNER JOIN 
                (
                SELECT * FROM db_temp.t_temp_helpdesk
                )b on a.site_code = b.site_code
                group by b.id_tiket
                order by b.created_date desc
            ";

            // echo "<pre><br><br><br><br>";print_r($sql);
            // echo "</pre>";
        }

        // echo "<pre><br>";
        // print_r($sql);
        // echo "</pre>";
        // die;

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    function get_image_by_ID($id)
    {
        $sql = "
            SELECT id, filename1, filename2, filename3 FROM db_temp.t_temp_helpdesk_detail
            where id = '$id'
        ";

        $proses = $this->db->query($sql)->row();
        return $proses;
    }

    public function get_note_helpdesk($id_helpdesk)
    {
        $id = $id_helpdesk;

        $sql = "
        SELECT a.status, b.* FROM db_temp.t_temp_helpdesk a left join db_temp.t_temp_helpdesk_detail b
        on a.id_tiket = b.id_tiket
        where md5(a.id_tiket) = '$id'
        order by created_date desc
        ";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function add_note_helpdesk($post)
    {
        $id_tiket = $post['id_tiket'];
        $tiket_email = md5($id_tiket);
        $data = [
            'id_tiket' => $id_tiket,
            'note' => $post['pesan'],
            'username' => $this->session->userdata('username'),
            'filename1' => $post['filename1'],
            'filename2' => $post['filename2'],
            'filename3' => $post['filename3'],
            'video' => $post['video'],
            'created_by' => $this->session->userdata('id'),
            'created_date' => $post['created_date'],
            'updated_by' => '',
            'last_updated' => $post['created_date'],
        ];
        // var_dump($data);die;
        $this->db->insert('db_temp.t_temp_helpdesk_detail', $data);

        $data1 = [
            'last_updated' => $post['created_date']
        ];
        $this->db->where('id_tiket', $id_tiket);
        $this->db->update('db_temp.t_temp_helpdesk', $data1);

        redirect("helpdesk/email_helpdesk/$tiket_email");
    }
}