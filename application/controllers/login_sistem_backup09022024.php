<!-- NOTE
- Menambahkan Min Berat
 -->
 <?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login_sistem extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mlogin','login');
        $this->load->library('email');
        $this->load->model('M_menu');
    }

    function index()
    {
        $this->formLogin();
    }

    function monitor($signature = 'monitor'){
        $data['title'] = 'Login Site';
        $data['url'] = 'login_sistem/prosesLogin/'.$signature;
        $data['signature'] = 'monitor';
        
        $this->load->view('login/formLogin',$data); 
    }

    public function formLogin($signature = '')
    {
        $data['title'] = 'Login Site';
        $data['url'] = "login_sistem/prosesLogin/$signature";
        $this->load->view('login/formLogin',$data); 
    }

    public function prosesLogin($signature = '')
    {
        $emailMpm = substr($this->input->post('email'),-21);
        $data['username'] = $this->input->post('username');
        $data['password'] = $this->input->post('password');
        
        $proses = $this->login->login($data);

        if ($proses) 
        {
            $id = $proses['id'];
            $username = $proses['username'];
            $name = $proses['name'];
            $jabatan = $proses['jabatan'];
            $supp = $proses['supp'];
            $level = $proses['level'];
            $email = $proses['email'];
            $status_email = $proses['status_email'];
            $address = $proses['address'];
            $company = $proses['company'];
            $kode_lang = $proses['kode_lang'];
            $min_berat = $proses['min_berat'];
    
            $data = array(
                'id'            => $id,
                'username'      => $username,
                'name'          => $name,
                'jabatan'       => $jabatan,
                'supp'          => $supp,
                'level'         => $level,
                'email'         => $email,
                'status_email'  => $status_email,
                'address'       => $address,
                'company'       => $company,
                'kode_lang'     => $kode_lang,
                'logged_in'     =>TRUE,
                'min_berat'     =>$min_berat
            );
            $this->session->set_userdata($data);
            if ($status_email == '0' || $status_email == null && strlen($username) > '3') {
                $this->validasiEmail();
            }else{

                if ($signature == 'monitor') {
                
                    redirect('monitor');

                } elseif ($signature == 'management_asset') {
                
                    redirect('management_asset/pengajuan_asset');

                }else{


                    $sql ="
                        SELECT id FROM mpm.`user` a
                        WHERE (LENGTH(username) = 3 and id = $id and a.active = 1) or a.level = 10 or left(a.username, 3) = 'MPI'
                    ";
                    $user = $this->db->query($sql)->row();
                    $match = count($user);

                    if ($match == 1) {

                        // $this->template('home', $data);
                        $update_hak_menu = $this->M_menu->get_strukur();

                        if ($this->session->userdata('id') == 704) {
                            redirect('dc/konfirmasi_dc');
                        }
                        // elseif (substr($this->session->userdata('username'),0,3) == 'MPI' || $this->session->userdata('username') == 'maserih' || $this->session->userdata('username') == 'rizki' || $this->session->userdata('username') == 'marketing') {
                        //     redirect('retur/');
                        // }
                        elseif ($this->session->userdata('level') == 10 || $this->session->userdata('level') == 4 || $this->session->userdata('level') == 3) {
                            redirect('management_office');
                        }
                        // elseif ($this->session->userdata('level') == 4) { // level 4 adalah dp
                        //     redirect('management_office');
                        // }elseif ($this->session->userdata('level') == 3) { // level 3 adalah user - user principal
                        //     redirect('management_inventory');
                        // }
                        else{
                            redirect('dashboard_dummy');
                        }
                
                    }else{
                        redirect('login/home');
                    }
                }                
            }
        }else{
            // echo "<script>alert('Password anda salah. Silahkan ulangi kembali');document.location='formLogin'</script>";
            $this->session->set_flashdata("pesan", "Login Gagal !! Cek kembali User dan Password anda");
            redirect('login_sistem/formLogin');
            die; 
        }
      
            
    }

    public function ValidasiEmail($pesan = null)
    {
        $data['title'] = 'Validasi Email';
        $data['url'] = 'login_sistem/prosesValidasiEmail';

        $data['pesan_password'] = $pesan['pesan_password'];
        $data['pesan_email'] = $pesan['pesan_email'];
        $data['get_data_user'] = $this->login->get_data_user();

        // echo "supp : ".$this->session->userdata('supp');

        // $level = $this->session->userdata('level');
        // if ($level == 10) { //jika mpm
        //     $this->load->view('login/validasiEmailMpm',$data); 
        // }
        $this->load->view('login/validasiEmailMpm',$data); 
    }

    public function prosesValidasiEmail()
    {
        // $emailMpm = substr($this->input->post('email'),-21);
        $data['email'] = $this->input->post('email');
        $data['password'] = $this->input->post('password');
        $data['password_old'] = $this->input->post('password_old');
        
        $pesan = $this->login->cek_data($data);
        if ($pesan['pesan_password'] == null && $pesan['pesan_email'] == null) {
            $proses = $this->login->konfirmasiEmail($data);        
        }else{
            $pesan = $this->ValidasiEmail($pesan);
        }
    }

    public function validasi($signature = ""){
        // echo $signature;
        $sql = "select username,email,password_enskripsi from db_temp.t_validasi_email a where signature = '$signature' order by id desc limit 1";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $a) {
            $username = $a->username;
            $email = $a->email;
            $password = $a->password_enskripsi;
        }

        // echo "username ".$username;
        // echo "email ".$email;
        // echo "password ".$password;

        $proses_update = $this->db->query("update mpm.user set status_email = 1,email='$email', password = '$password' where username = '$username'");
        if ($proses_update) {
            echo "<script>alert('Proses validasi email selesai. Terima kasih. Silahkan login ke website');document.location='../login_sistem'</script>"; 
        }else{
            echo "<script>alert('Proses validasi email gagal. Harap ulangi proses anda kembali');document.location='../login_sistem'</script>"; 
        }

        
    }

    public function management_asset($signature = 'management_asset')
    {
        $data['title'] = 'Login Site';
        $data['url'] = 'login_sistem/prosesLogin/'.$signature;
        $data['signature'] = 'monitor';
        
        $this->load->view('login/formLogin',$data); 
    }
    

}
?>
