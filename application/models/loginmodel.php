<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Loginmodel extends CI_Model
{
    
    public function login($username, $password){

      $q = $this->db->select('password, id, nim')->from('tbl_users')->where('username', $username)->get()->row();

      if ($q == "") {
        return array('status' => 204, 'message' => 'Username not found');
      } else {
        $hashed_password  = $q->password;
        $id               = $q->id;
        $nim              = $q->nim;

        $passwordMD5 = substr( md5($password), 0, 32);

        if (hash_equals($hashed_password, $passwordMD5)) {

          $last_login = date("Y-m-d H:i:s");
          $token = crypt(substr( md5(rand()), 0, 7));
          $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));

          $this->db->trans_start();
          $this->db->where('id', $id)->update('tbl_users', array('last_login'=>$last_login));
          $this->db->insert('tbl_users_authentication', array('users_id'=>$id,
                              'token'=>$token, 'expired_at'=>$expired_at));

          if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return array('status'=> 500, 'message'=>'Internal server error.');
          } else {
            $this->db->trans_commit();
            return array('status'=>200, 'message'=> 'Successfully login. ', 'id'=> $id, '$token'=> $token, 'nim'=>$nim);
          }
          

        } else {
          return array('status'=> 204, 'message' => 'Wrong password.');
        }
        

      }
      

    }
    

}
?>
