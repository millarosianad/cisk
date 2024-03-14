<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mlogin extends CI_Model {

    function registrasi($data)
    {
        $nama = $data['nama'];
        $email = $data['email'];  
        $acak = random_string('alnum',6);
        $pass=md5($acak);
        $created_date = date('Y-m-d H:i:s');
        
        //cek apakah sudah terdaftar sebelumnya
        $cek = "select * from m_user where email = '$email'";
        $proses = $this->db->query($cek)->num_rows();
        if($proses){
            echo "<script>alert('Email anda sudah terdaftar. Jika lupa password, Silahkan masuk ke menu reset password');document.location='formLogin'</script>";
        }else{
            $sql = "
                insert into m_user
                select '', '$nama', '$email', '$pass', '', '$created_date', '$created_date',1
            ";
            $proses = $this->db->query($sql);
            if($proses)
            {
                echo "<script>alert('Registrasi berhasil. Sistem akan mengirim password ke email anda');document.location='emailLogin/$acak'</script>";
            }
        }
    }

    function lupaPassword($data)
    {
        $email = $data['email'];  
        $acak = random_string('alnum',6);
        $pass=md5($acak);
        $updated_date = date('Y-m-d H:i:s');
        
        //cek apakah sudah terdaftar sebelumnya
        // $cek = "select * from m_user where email = '$email'";
        // $proses = $this->db->query($cek)->num_rows();

        $cariId = "select id from m_user where email = '$email'";
        $proses = $this->db->query($cariId)->row();
        $id = $proses->id;

        if($proses){
            // echo "<script>alert('Sistem akan mengirim password ke email anda');document.location='emailLupaPassword/$id'</script>";

            $sql = "update m_user set password = '$pass', last_updated = '$updated_date' where id = $id";
            $proses = $this->db->query($sql);
            // var_dump($proses);
            if ($proses) {
                echo "<script>alert('Reset password berhasil. Sistem akan mengirim password ke email anda');document.location='emailLogin/$acak'</script>";
            }else{

            }

        }else{
            echo "<script>alert('Email anda belum terdaftar. Silahkan registrasikan email anda');document.location='formRegistrasi'</script>";
        }
    }

    function login($data)
    {
        $username = $data['username']; 
        $password = $data['password'];  
        $pass=md5($password);
        
        $sql = "
            select id,username,name,jabatan,supp,email,password,level,status_email,kode_lang,company,address, min_berat from mpm.user where username = '$username'
            and password = '$pass' and active = 1
        ";
        $proses = $this->db->query($sql)->row();
        // echo "proses : ".$proses;

        if ($proses) {
            // echo "x";
            $data = array(
                'id'            => $proses->id,
                'username'      => $proses->username,
                'name'          => $proses->name,
                'jabatan'       => $proses->jabatan,
                'supp'          => $proses->supp,
                'email'         => $proses->email,
                'level'         => $proses->level,
                'status_email'  => $proses->status_email,
                'kode_lang'     => $proses->kode_lang,
                'company'       => $proses->company,
                'address'       => $proses->address,
                'min_berat'     => $proses->min_berat,
            );
            return $data;
        }else{
            return array();
            // echo "y";
            // echo "<script>alert('Password anda salah. Silahkan ulangi kembali');document.location='formLogin'</script>";
        }


    }

    function ubahPassword($data)
    {
        echo "password : ".$data['password']."<br>";
        $pass=md5($data['password']);
        $id = $data['id'];
        echo "pass : ".$pass;

        $data = array(
            'password' => $pass,
            'last_updated' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        $proses = $this->db->update('m_user', $data);
        if ($proses) {
            return $proses;
        }else{
            return false;
        }
        
    }

    function validasiEmail($data)
    {        
        $level = $this->session->userdata('level');
        $email = $data['email']; 
        $sess_supp = $this->session->userdata('supp');
        $sess_username = $this->session->userdata('username');
        $emailMPM = substr($email,-21); //muliaputramandiri.com
        $emailDelto = substr($email,-12); //deltomed.com
        $emailUs = substr($email,-14); //ultrasakti.com
        $emailMarguna = substr($email,-10); //marguna.id
        $emailIntrafood = substr($email,-13); //intrafood.net
        $emailStrive = substr($email,-19); //striveindonesia.com
        $emailHni = substr($email,-17); //heavenlyblush.com
        $lengthUsername = strlen($sess_username);

        if ($sess_supp == '000' && $level == 10) { // not dp == mpm employee
            if ($emailMPM == "muliaputramandiri.com") {
                return $this->konfirmasiEmail($data);
            }else{
                return $this->invalidEmail($level);
            }
        }elseif($sess_supp == '001'){
            if ($emailDelto == "deltomed.com") {
                return $this->konfirmasiEmail($data);
            }else{
                return $this->invalidEmail($level);
            }
        }elseif($sess_supp == '002'){
            if ($emailMarguna == "marguna.id") {
                return $this->konfirmasiEmail($data);
            }else{
                return $this->invalidEmail($level);
            }
        }elseif($sess_supp == '005'){
            if ($emailUs == "ultrasakti.com") {
                return $this->konfirmasiEmail($data);
            }else{
                return $this->invalidEmail($level);
            }
        }elseif($sess_supp == '012'){
            if ($emailIntrafood == "intrafood.net") {
                return $this->konfirmasiEmail($data);
            }else{
                return $this->invalidEmail($level);
            }
        }elseif($sess_supp == '013'){
            if ($emailStrive == "striveindonesia.net") {
                return $this->konfirmasiEmail($data);
            }else{
                return $this->invalidEmail($level);
            }
        }elseif($sess_supp == '014'){
            if ($emailHni == "heavenlyblush.net") {
                return $this->konfirmasiEmail($data);
            }else{
                return $this->invalidEmail($level);
            }
        }else{
            return $this->konfirmasiEmail($data);
        }
    }

    public function konfirmasiEmail($data){
        
        
        $password = $data['password'];
        $password_enskripsi = md5($password);
        $email = $data['email'];
        $username = $this->session->userdata('username');
        $supp = $this->session->userdata('supp');
        $signature = md5($email.$username.$supp);
        $data['signature'] = md5($email.$username.$supp);
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

        $sql = "
            insert into db_temp.t_validasi_email
            select '','$username', '$signature','$email','$password','$password_enskripsi','1',$tgl_created
        ";
        $this->db->query($sql);

        $from = "suffy@muliaputramandiri.com";
        $to = $email;
        $cc = "suffy@muliaputramandiri.com";
        $subject = "site.muliaputramandiri.com - Validasi Email";
        // $message = "This email is sent by system";
        $message = $this->load->view("login/templateValidasiEmail",$data,TRUE);
        $this->load->library('email');
        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user']    = 'support@muliaputramandiri.com';
        $config['smtp_pass']    = 'support123!@#';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     = "html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        // $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://smtp.gmail.com';
        // $config['smtp_port']    = '465';
        // $config['smtp_timeout'] = '300';
        // $config['smtp_user']    = 'suffy.yanuar@gmail.com';
        // $config['smtp_pass']    = 'suffy123!@#';
        // $config['charset']      = 'utf-8';
        // $config['newline']      = "\r\n";
        // $config['mailtype']     ="html";
        // $config['use_ci_email'] = TRUE;
        // $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if($send == 1){
            // echo "<script>alert('Kami akan mengirimkan tautan ke email anda');</script>"; 
            echo "<script>alert('Kami mengirimkan tautan ke email anda. Silahkan buka email anda dan klik tautan tersebut. Buka spam jika tidak terdapat di kotak masuk / inbox'); window.location = '../login_sistem';</script>";
            // echo "<script>alert('Kami mengirimkan tautan ke email anda. Silahkan buka email anda dan klik tautan tersebut. Buka spam jika tidak terdapat di kotak masuk / inbox);document.location='../login_sistem'</script>"; 
        }else{
            echo "<script>alert('Pengiriman email tautan gagal, mungkin koneksi tidak stabil. Harap ulangi proses anda kembali');document.location='../login_sistem'</script>"; 
        }
    }

    public function invalidEmail($level){
        // echo "level : ".$level;
        // echo "<script>alert('Email yang anda masukkan salah. Silahkan ulangi kembali');document.location='login_sistem'</script>";
        if ($level == 10) {
            $pesan = "harap menggunakan email perusahaan yaitu @muliaputramandiri.com";
            return $pesan;
        }else{
            $pesan = "harap menggunakan email perusahaan";
            return $pesan;
        }
    }

    public function get_data_user(){
        $id = $this->session->userdata('id');
        $sql = "
            select a.id,a.username,a.password,a.email
            from mpm.user a
            where a.id = $id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function cek_data($data){
        $password = $data['password'];
        $password_old = $data['password_old'];
        $email = $data['email'];
        // echo "<br>password : ".$password;
        // echo "<br>password_old : ".$password_old;

        if (md5($password) == $password_old) {
            // $data['pesan_password'] = "password yang anda masukkan sama dengan password sebelumnya. Harap gunakan password lain";
            $data_pesan['pesan_password'] = "password yang anda masukkan sama dengan password sebelumnya. Harap gunakan password lain";
            // return $pesan;
        }else{
            $data_pesan['pesan_password'] = null;
            // return true;
        }

        $cek_email = $this->cek_email($email);
        // echo "cek_email : ".$cek_email;
        if ($cek_email == 1) {
            // $data['pesan_email'] = "password yang anda masukkan sama dengan password sebelumnya. Harap gunakan password lain";
            $data_pesan['pesan_email'] = null;
        }else{
            $data_pesan['pesan_email'] = $cek_email;
        }
        

        return $data_pesan;
    }

    public function cek_email($email){
        // echo "email : ".$email;
        $level = $this->session->userdata('level');
        $sess_supp = $this->session->userdata('supp');
        // echo "supp : ".$sess_supp;

        $emailMPM = substr($email,-21); //muliaputramandiri.com
        $emailDelto = substr($email,-12); //deltomed.com
        $emailUs = substr($email,-14); //ultrasakti.com
        $emailMarguna = substr($email,-10); //marguna.id
        $emailIntrafood = substr($email,-13); //intrafood.net
        $emailStrive = substr($email,-19); //striveindonesia.com
        $emailHni = substr($email,-17); //heavenlyblush.com

        if ($sess_supp == '000' && $level == 10) 
        { // not dp == mpm employee
            if ($emailMPM == "muliaputramandiri.com") {

            }else{
                $pesan = "harap gunakan email perusahaan, yaitu @muliaputramandiri.com";
                return $pesan;
            }
        }elseif($sess_supp == '001'){
            if ($emailDelto == "deltomed.com") {
                
            }else{
                $pesan = "harap gunakan email perusahaan, yaitu @deltomed.com";
                return $pesan;
            }
        }elseif($sess_supp == '002'){
            if ($emailMarguna == "marguna.id") {
                
            }else{
                $pesan = "harap gunakan email perusahaan, yaitu @marguna.id";
                return $pesan;
            }
        }elseif($sess_supp == '005'){
            if ($emailUs == "ultrasakti.com") {
                
            }else{
                $pesan = "harap gunakan email perusahaan, yaitu @ultrasakti.com";
                return $pesan;
            }
        }elseif($sess_supp == '012'){            
            if ($emailIntrafood == "intrafood.net") {
            }else{                
                $pesan = "harap gunakan email perusahaan, yaitu @intrafood.net";
                return $pesan;
            }
        }elseif($sess_supp == '013'){
            if ($emailStrive == "striveindonesia.net") {
                
            }else{
                $pesan = "harap gunakan email perusahaan, yaitu @striveindonesia.net";
                return $pesan;
            }
        }elseif($sess_supp == '014'){
            if ($emailHni == "heavenlyblush.net") {
               
            }else{
                $pesan = "harap gunakan email perusahaan, yaitu @heavenlyblush.bet";
                return $pesan;
            }
        }else{ // untuk dp
            // return $this->konfirmasiEmail($data);
        }

    }

    

}

/* End of file model_sales.php */
/* Location: ./application/models/model_sales.php */