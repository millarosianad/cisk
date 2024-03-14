<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model {

    public function get_messages($offset, $limit) {
        $sql = "SELECT * FROM testing.messages ORDER BY id DESC LIMIT $offset,$limit";
        $q = $this->db->query($sql);
        return $q->result();
    }

    public function load_messages($msg_id) {
        $sql = "SELECT * FROM testing.messages where id < $msg_id order by id desc limit 10";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        // $q = $this->db->query($sql);
        // return $q->result();

        return $sql;
    }

}