<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_micro extends CI_Model {

    public function show_processlist(){
        $sql = "show processlist";
        $proses = $this->db->query($sql)->result();
        // echo "<pre>";
        // print_r($proses);
        // echo "</pre>";

        $sum = 0;
        foreach ($proses as $key) {
            // echo "command : ".$key->Command;
            if ($key->Command == 'Query') {
                $point = 1;
            }else {
                $point = 0;
            }
            // $point+= $point;
            // echo "<hr>point : ".$point;
            $sum+= $point;
        }
        // echo "<hr>";
        // echo "sum : ".$sum;
        return $sum;
    }
    
}