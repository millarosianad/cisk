<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_request extends CI_Model 
{    
    public function get_null($id){
        $sql = "
            select count(*) as total_null
            from dbrest.t_request_customer a
            where a.log_id = $id and a.status_approval is null
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $total_null = $key->total_null;
        }
        return $total_null;
    }

    public function history(){
    
        $sql = "
            
        select 	a.id,a.signature,a.tgl_request,a.nocab,a.customerid,
                sum(a.pending) as total_pending, sum(a.approve) as total_approve, sum(a.reject) as total_reject,
                sum(a.pending) + sum(a.approve) + sum(a.reject) as total_customerid, b.branch_name,b.nama_comp,b.kode_comp
        from
        (
            select 	a.signature,a.id, date(a.created_date) as tgl_request,a.nocab,
                    b.customerid,b.status_approval,
                    IF(b.status_approval is null,1,0) as pending,
                    IF(b.status_approval = 1,1,0) as approve,
                    IF(b.status_approval = 0,1,0) as reject
            from 	dbrest.t_log_request a INNER JOIN 
            (
                SELECT	a.log_id, a.customerid,a.status_approval
                from 	dbrest.t_request_customer a
            )b on a.id = b.log_id
        )a left join 
        (
            SELECT a.branch_name,a.nama_comp,a.nocab,a.kode_comp
            FROM
            (
                SELECT  CONCAT(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.nocab,a.kode_comp
                from    mpm.tbl_tabcomp a
                WHERE   a.`status` = 1
                GROUP BY kode
            )a INNER JOIN 
            (
                SELECT CONCAT(a.kode_comp,a.nocab) as kode, a.nocab
                FROM db_dp.t_dp a
                WHERE a.tahun = YEAR(NOW())
                GROUP BY a.nocab
            )b on a.kode = b.kode
        )b on a.nocab = b.nocab
        GROUP BY a.id
        order by id desc
        ";
        $proses = $this->db->query($sql)->result();
        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        if ($proses) {
            return $proses;
        }else{
            return array();
        }        
    }

    public function detail_request($id){
    
        $sql = "
        select 	a.id,b.siteid,b.customerid,substr(b.nama_customer,1,20) as nama_customer,
        a.nocab,c.branch_name,c.nama_comp,
        b.typeid_current,b.typeid_request,b.classid_current,b.classid_request,b.no_request,b.status_approval,b.segmentid_current,b.segmentid_request
        from dbrest.t_log_request a INNER JOIN (
            select a.siteid,a.customerid,a.nama_customer,a.typeid_current,a.typeid_request,a.classid_current,a.classid_request,a.no_request,a.log_id,a.status_approval, a.				
            segmentid_current, a.segmentid_request
            from dbrest.t_request_customer a
        )b on a.id = b.log_id LEFT JOIN
        (
            SELECT a.branch_name,a.nama_comp,a.nocab
            FROM
            (
                SELECT  CONCAT(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.nocab
                from    mpm.tbl_tabcomp a
                WHERE   a.`status` = 1
                GROUP BY kode
            )a INNER JOIN 
            (
                SELECT CONCAT(a.kode_comp,a.nocab) as kode, a.nocab
                FROM db_dp.t_dp a
                WHERE a.tahun = YEAR(NOW())
                GROUP BY a.nocab
            )b on a.kode = b.kode
        )c on a.nocab = c.nocab
        where a.id = $id
        ";
        echo "<br><br><br><br><br><br><pre>";
        print_r($sql);
        echo "</pre>";
        
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }        
    }

    public function proses_detail_request($data){
        $created_by=$this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';
    
        $note = $data['note'];
        $id_log = $data['id_log'];
        $no_request = $data['no_request'];
        $status_approve = $data['status_approve'];

        // echo $note;
        // echo $id_log;
        // echo $no_request;
        // echo $status_approve;
        
        $sql = "
            update dbrest.t_request_customer a
            set a.status_approval = $status_approve, a.created_by = $created_by, a.created_date = $created_date, a.note = '$note'
            where a.log_id = $id_log and a.no_request in ($no_request)
        ";
        $proses = $this->db->query($sql);
        $sql = "
            update dbrest.t_request_customer_detail a
            set a.status_approval = $status_approve, a.created_by = $created_by, a.created_date = $created_date, a.note = '$note'
            where a.log_id = $id_log and a.no_request in ($no_request)
        ";
        $proses = $this->db->query($sql);

        $sql = "select $status_approve as status_approve, $id_log as id, if($status_approve = 1,'approve',if($status_approve = 0,'reject',if($status_approve is null, 'pending',$status_approve))) as status";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function proses_approval_request($status_approve,$id,$kode_comp){
        $created_by=$this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';

        if ($status_approve == 1) {
            $pesan = "approve";
        }else{
            $pesan = "reject";
        }
    
        $sql = "
            update dbrest.t_request_customer_detail a
            set a.status_approval = $status_approve,a.created_date = $created_date,a.created_by = $created_by
            where a.log_id = $id
        ";
        $proses = $this->db->query($sql);
        $sql = "
            update dbrest.t_request_customer a
            set a.status_approval = $status_approve, a.created_by = $created_by, a.created_date = $created_date
            where a.log_id = $id
        ";
        $proses = $this->db->query($sql);
        // echo $proses;
        
        if ($proses) {
            // redirect('request/history/');
            // return $proses;
            // $this->email_to_dp($status_approve,$id,$kode_comp);
            return $pesan;
        }else{
            return array();
        }
    }

    public function generate_detail($id){
        $sql = "
        select 	a.kode,a.signature, a.signaturex, a.branch_name, a.nama_comp, 
				a.customerid, a.nama_customer,
				concat(a.kode_type_current,' (',a.nama_type_current,') ',' -> ',a.kode_type_request,' (',a.nama_type_request,' )') as type,				
				concat(a.classid_current,' (',a.nama_class_current,') ',' -> ',a.classid_request,' (',a.nama_class_request,') ') as class,
				concat(a.segmentid_current,' -> ',a.segmentid_request) as segment,
				IF(a.status_approval is null ,'pending',IF(a.status_approval = 1,'approve',IF(a.status_approval = 0 ,'reject',a.status_approval))) as status,
                a.created_by as approved_by, a.created_date,a.created_by,b.username
        from dbrest.t_request_customer_detail a left join mpm.user b 
                    on a.created_by = b.id
        where a.log_id = $id";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";


        $hasil = $this->db->query($sql);
        $file = fopen(APPPATH . '/../assets/file/request/'.$id.'.csv', 'wb');

        $csv_fields=array();
        $csv_fields[] = 'No';
        $csv_fields[] = 'Branch';
        $csv_fields[] = 'SubBranch';
        $csv_fields[] = 'CustomerId';
        $csv_fields[] = 'NamaCustomer';
        $csv_fields[] = 'Type';
        $csv_fields[] = 'Class';
        $csv_fields[] = 'segment';
        $csv_fields[] = 'Status';
        $csv_fields[] = 'ProsesBy';
        $csv_fields[] = 'approvedDate';
        fputcsv($file, $csv_fields);

        foreach ($hasil->result() as $row) 
        {
            $signaturex = $row->signaturex;
            $branch_name = $row->branch_name;
            $nama_comp = $row->nama_comp;
            $customerid = $row->customerid;
            $nama_customer = $row->nama_customer;
            $type = $row->type;
            $class = $row->class;
            $segment = $row->segment;
            $status = $row->status;
            $username = $row->username;
            $created_date = $row->created_date;

            fputcsv($file, array($id,$branch_name,$nama_comp,$customerid,$nama_customer,$type,$class,$segment,$status,$username,$created_date));
        }
    }

    public function get_detail($id){
        $sql = "
        select * from dbrest.t_request_customer_detail a
        where a.log_id = $id";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function email_to_dp($status_approve,$id,$kode_comp){
        echo "status_approve : ".$status_approve;
        echo "id : ".$id;

        $get_email_dp = $this->email_dp($kode_comp);
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        }

        echo "email_dp : ".$email_dp;

        $data['email_dp'] = $email_dp;


        $this->message_email_x($log_id,$tahun,$nocab);
        $data['get_message'] = $this->message_email($log_id);
        // var_dump($get_message);
        foreach ($data['get_message'] as $key) {
            $signaturex = $key->signaturex;
            $signature = $key->signature;
        }
        $data['signature']=$signature;
        $data['signaturex']=$signaturex;
        $data['log_id']=$log_id;

        // echo "rm : ".$email_rm."<br>";
        // echo "dp : ".$email_dp;

        // $from = "suffy@muliaputramandiri.com";
        $from = "suffy.yanuar@gmail.com";
        // $to = $email_rm;
        $to = "suffy.yanuar@gmail.com";
        $cc = "suffy.yanuar@gmail.com";
        // $cc = $email_dp;
        $subject = "site.muliaputramandiri.com|Request Perubahan Data Outlet / Customer";
        // $message = "Log Id = ".$log_id;
        // $message = $this->load->view("login/templateValidasiEmail",$data,TRUE);
        $message = $this->load->view("request/template_email_to_approval",$data,TRUE);
        $this->load->library('email');
        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user']    = 'suffy.yanuar@gmail.com';
        $config['smtp_pass']    = 'yanuar123!@#';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/request/'.$log_id.'.csv');
        // $send = $this->email->send();
        // echo $this->email->print_debugger();
        // if ($send) {
        //     return $signature;
        // }else{
        //     return 0;
        // }
    }

    public function message_email_x($log_id,$tahun,$nocab){
        $sql = "
        select 	a.id,right(a.signature,5) as signaturex,
                h.branch_name,h.nama_comp,
                /*a.filename,a.nocab,a.tahun,a.bulan,a.tanggal,a.signature,a.created_date,
                b.siteid,*/b.customerid,b.nama_customer,
                b.typeid_current as kode_type_current,c.nama_type as nama_type_current,/*c.sektor as sektor_current,c.segment as segment_current,*/
                b.typeid_request as kode_type_request,d.nama_type as nama_type_request,/*d.sektor as sektor_request, d.segment as segment_request,*/
                b.classid_current,e.jenis as nama_class_current,/*e.`group`,*/
                b.classid_request, f.jenis as nama_class_request
        from dbrest.t_log_request a LEFT JOIN dbrest.t_request_customer b
            on a.id = b.log_id LEFT JOIN 
            (
                select a.kode_type,a.nama_type,a.sektor,a.segment
                from mpm.tbl_bantu_type a
            )c on b.typeid_current = c.kode_type LEFT JOIN
            (
                select a.kode_type,a.nama_type,a.sektor,a.segment
                from mpm.tbl_bantu_type a
            )d on b.typeid_request = d.kode_type LEFT JOIN
            (
                select a.kode,a.jenis,a.`group`
                from mpm.tbl_tabsalur a
            )e on b.classid_current = e.kode LEFT JOIN
            (
                select a.kode,a.jenis,a.`group`
                from mpm.tbl_tabsalur a
            )f on b.classid_request = f.kode LEFT JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode,a.nocab
                from db_dp.t_dp a
                where tahun = $tahun and nocab = $nocab
            )g on a.nocab = g.nocab LEFT JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode, a.customerid, a.branch_name,a.nama_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1 and nocab = $nocab
                GROUP BY concat(a.kode_comp,a.nocab)
            )h on g.kode = h.kode
            where a.id = $log_id
        ";
        // $proses = $this->db->query($sql)->result();
        // if ($proses) {
        //     return $proses;
        // }else{
        //     return array();
        // }

        $hasil = $this->db->query($sql);
        $file = fopen(APPPATH . '/../assets/request/'.$log_id.'.csv', 'wb');

        $csv_fields=array();
        $csv_fields[] = 'No';
        $csv_fields[] = 'Branch';
        $csv_fields[] = 'SubBranch';
        $csv_fields[] = 'CustomerId';
        $csv_fields[] = 'NamaCustomer';
        $csv_fields[] = 'TypeCurrent';
        $csv_fields[] = 'NamaTypeCurrent';
        $csv_fields[] = 'TypeRequest';
        $csv_fields[] = 'NamaTypeRequest';
        $csv_fields[] = 'ClassIdCurrent';
        $csv_fields[] = 'NamaClassCurrent';
        $csv_fields[] = 'ClassIdRequest';
        $csv_fields[] = 'NamaClassRequest';
        fputcsv($file, $csv_fields);

        foreach ($hasil->result() as $row) 
        {
            $signaturex = $row->signaturex;
            $branch_name = $row->branch_name;
            $nama_comp = $row->nama_comp;
            $customerid = $row->customerid;
            $nama_customer = $row->nama_customer;
            $kode_type_current = $row->kode_type_current;
            $nama_type_current = $row->nama_type_current;
            $kode_type_request = $row->kode_type_request;
            $nama_type_request = $row->nama_type_request;
            $classid_current = $row->classid_current;
            $nama_class_current = $row->nama_class_current;
            $classid_request = $row->classid_request;
            $nama_class_request = $row->nama_class_request;

            fputcsv($file, array($log_id,$branch_name,$nama_comp,$customerid,$nama_customer,$kode_type_current,$nama_type_current,$kode_type_request,$nama_type_request, $classid_current, $nama_class_current, $classid_request,$nama_class_request));
        }
    }

    public function email_dp($kode_comp){
        $sql = "
        select a.email
        from mpm.`user` a
        WHERE username ='$kode_comp'       
        ";

        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }    

    public function get_nocab_tahun($id){
        $proses = $this->db->query("select nocab,tahun from dbrest.t_log_request a where a.id = $id")->result();
        if ($proses) {
            // foreach($proses as $a){
            //     $nocab = $a->nocab;
            // }
            // return $nocab;
            return $proses;
        }else{
            return array();
        }
    }

    public function getGrupassetcombo()
    {
        $sql='select id,nama_kategori from mpm.tbl_kategori_complain';
        return $this->db->query($sql);
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

    public function view_complain()
    {
        $id=$this->session->userdata('id');
        $this->db->order_by("tbl_complain_system.id", "desc");
        //$this->db->where('userid_pelapor', $id);
        $this->db->join('user', 'tbl_complain_system.userid_pelapor = user.id','left');
        $this->db->join('mpm.tbl_kategori_complain', 'tbl_kategori_complain.id = tbl_complain_system.id_kategori','left');
        $this->db->join('mpm.tbl_status_complain', 'tbl_complain_system.id_status = tbl_status_complain.id','left');
        $this->db->select('tbl_complain_system.id, tbl_complain_system.userid_pelapor, tbl_complain_system.nama_pelapor, tbl_complain_system.tgl_ajuan, tbl_kategori_complain.nama_kategori, tbl_complain_system.masalah,tbl_status_complain.nama_status,tbl_complain_system.file, tbl_complain_system.nama_it, user.username,tbl_complain_system.note_tambahan');
        //$this->db->where('tbl_complain_system.userid_pelapor',$id);
        $hasil = $this->db->get('mpm.tbl_complain_system');

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function view_complain_user()
    {
        $id=$this->session->userdata('id');
        $this->db->order_by("tbl_complain_system.id", "desc");
        $this->db->where('userid_pelapor', $id);
        $this->db->join('mpm.tbl_kategori_complain', 'tbl_kategori_complain.id = tbl_complain_system.id_kategori','left');
        $this->db->join('mpm.tbl_status_complain', 'tbl_complain_system.id_status = tbl_status_complain.id','left');
        $this->db->select('tbl_complain_system.id, tbl_complain_system.userid_pelapor, tbl_complain_system.nama_pelapor, tbl_complain_system.tgl_ajuan, tbl_kategori_complain.nama_kategori, tbl_complain_system.masalah,tbl_status_complain.nama_status,tbl_complain_system.file,tbl_complain_system.nama_it');
        $this->db->where('tbl_complain_system.userid_pelapor',$id);
        $hasil = $this->db->get('mpm.tbl_complain_system');

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

    public function view_ticket()
    {
         $sql = '
                select *
                from db_help.t_ticket
                order by id desc
                ';
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
                
            /* END PROSES TAMPIL KE WEBSITE */
      
    }

    public function view_ticket_detail($id)
    {
         $sql = "
         select a.id, c.nopo, c.company
         from db_help.t_ticket a INNER JOIN db_help.t_ticket_detail b
             on a.id = b.id_ref LEFT JOIN mpm.po c 
             on b.id_po = c.id
         where a.id = $id
                ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
                
            /* END PROSES TAMPIL KE WEBSITE */
      
    }

    public function get_po($from,$to)
    {
        // $x = $this->uri->segment('3');
        // $y = $this->uri->segment('4');
        $sql = "
            select *
            from mpm.po a
            where nopo is not null and supp='001' and date(tglpo) between '$from' and '$to'
        ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
                
            /* END PROSES TAMPIL KE WEBSITE */
      
    }

    public function submit_ticket($data){
        $created_by=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $created_date='"'.date('Y-m-d H:i:s').'"';
    
        $deskripsi = $data['deskripsi'];
        $sql = "insert into db_help.t_ticket (deskripsi,created_date,created_by,status_deleted,status_ticket) values ('$deskripsi',$created_date,$created_by,'0','1')";
        $proses = $this->db->query($sql);

        $sql = "select id from db_help.t_ticket a where a.created_by = $created_by and a.created_date = $created_date";
        $proses = $this->db->query($sql)->result();

        foreach ($proses as $key) {
            $id_ticket = $key->id;
        }

        $po_id = $data['po_id'];

        foreach ($po_id as $key) {
            // echo $key;
            $data = array(
                "id_ref"    => $id_ticket,
                "id_po"     => $key,
            );
            $proses_t_detail = $this->db->insert("db_help.t_ticket_detail", $data);
        }

        if($proses_t_detail){
            return $id_ticket;
        }else{
            return 0;
        }




    }

}