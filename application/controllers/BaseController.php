<?php

header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends MY_Controller {

     function __construct() {
        parent::__construct();
        $this->load->model("BaseModel");
    }

    public function index() {
        // $this->load->helper('database');
        
        $this->load->model("BaseModel");

        $limit = 10;
        $offset = 0;
        $data['messages'] = $this->BaseModel->get_messages($offset, $limit);
        $this->load->view('load_data_view', $data);
    }

    public function load_messages() {

        echo "a : ".$_POST['msg_id'];
        if (isset($_POST['msg_id']) && !empty($_POST['msg_id'])) {
            $data['messages'] = $this->BaseModel->load_messages($_POST['msg_id']);
            $page = $this->load->view("load_ajax_messages_view", $data, true);
            echo json_encode(array("result" => "Success", "page" => $page, "msg" => ""));
        } else {
            echo json_encode(array("result" => "Fail", "page" => "", "msg" => "Server problem. Try after sometime."));
        }

        // $test = $this->BaseModel->load_messages('1');

        // var_dump($test);
    }

}