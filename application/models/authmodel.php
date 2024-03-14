<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Authmodel extends CI_Model
{
    var $client_service = "a";
    var $auth_key = "b";

  public function check_auth_client(){

    $input_client_service = $this->input->get_request_header('Client_Service',true);
    $input_auth_key = $this->input->get_request_header('Auth_Key', TRUE);

    echo "<pre>";
    print($input_client_service);
    echo "</pre>";
    /*
    if ($input_client_service == $this->client_service && $input_auth_key == $this->auth_key) {
      return true;
    } else {
      return json_output(401, array('status' => 401, 'message' => 'Unathorized Headers.'));
      //echo " a : ".$input_client_service;
    
    }*/
    
    
  }  
    


}
?>
