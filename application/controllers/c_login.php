<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_login extends CI_Controller
{
    
    public function signin(){
    	$method = $_SERVER['REQUEST_METHOD'];
    	if ($method != 'POST') {
    		json_output(400, array('status' => 400, 'message' => 'Bad Request'));
    	} else {
    		

    		$check_auth_client = $this->authmodel->check_auth_client();
    		/*
    		if ($check_auth_client == TRUE) {

    			
    			$params = json_decode(file_get_contents('php://input'), TRUE);
    			$username = $params['username'];
    			$password = $params['password'];

    			$response = $this->loginmodel->login($username, $password);
    			json_output($response['status'], $response);
    		
    		} */
    		
    	}
    	
    }
 

}
?>
