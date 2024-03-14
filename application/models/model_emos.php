<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_emos extends CI_Model 
{

    public function insert_faktur_order($data){

        $CustomerPONumber = $data['CustomerPONumber'];

        $data = [
            "CustomerPONumber"=>$CustomerPONumber,
            "linePO"=>array([
                'kodeProduct' => "abc",
                'quantityPO' => 10
            ])
        ];

        $client = new Client();
        $response = $client->request('POST', "http://localhost:81/backend/api/emos/order", [
            'json' =>  $data
                ]);
        $result = json_decode($response->getBody()->getContents(),true);
        return $result;
    }



}