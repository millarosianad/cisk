<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_uptime extends CI_Model 
{
    
	public function get_status_uptime(){		
		$client = new Client();
	
			$response = $client->request('GET', "http://backup.muliaputramandiri.com:81/restapi/api/master_data/cek_uptime?X-API-KEY=123&token=64646ecaf773b3192034998fccbb27b5", [
				'query' => [
					'X-API-KEY' => '123',
                    'token' => '64646ecaf773b3192034998fccbb27b5'
				]
			]);

            if ($response) {
                echo "aaaaaaaaaaaaaaa";
            }else{
                echo "bbbbbbbbbbbbbbb";
            }

            var_dump($response);
            die;
			$result = json_decode($response->getBody()->getContents(),true);
            var_dump($result);
            
			if($result != array())
			{
				echo "aman";


			}else{
				echo "nnn";
			}



	}

}