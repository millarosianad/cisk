<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_event extends CI_Model 
{
    public function get_pelaporan_event($from = '', $to = '', $signature= ''){
        
        if ($from == '' || $to == '') {
            $params_periode = '';
        }else{
            $params_periode = " and a.event_from between '$from 00:00:00' and '$to 23:59:59' ";
        }

        if ($signature) {
            $params_signature = " and a.signature = '$signature' ";
        }else{
            $params_signature = "";
        }
        
        $query = "
            select a.*, b.*
            from site.management_event_pelaporan a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a 
            )b on a.created_by = b.id
            where a.deleted_at is null $params_periode
            $params_signature
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function generate($created_at){

        $bulan_now = date('m',strtotime($created_at));
        $romawi = $this->getRomawi($bulan_now);
        $tahun_now = date('Y');

        $query = "
            select a.no_pelaporan_event, substr(a.no_pelaporan_event,7,5) as urut
            from site.management_event_pelaporan a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_pelaporan_event is not null
            ORDER BY a.id desc
            limit 1
        ";

        $no_current = $this->db->query($query);
        if ($no_current->num_rows() > 0) {            
            $params_urut = $no_current->row()->urut + 1;
            if (strlen($params_urut) === 1) {
                $generate = "EVENT-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "EVENT-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "EVENT-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "EVENT-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    function getRomawi($bln){
        switch ($bln){
            case 1: 
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public function get_review($id_pelaporan){
        $query = "
            select a.*, b.*
            from site.management_event_review a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a 
            )b on a.created_by = b.id
            where a.id_pelaporan = $id_pelaporan
        ";
        return $this->db->query($query);
    }

}