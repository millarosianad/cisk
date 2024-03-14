<?php
class LiveTable_model extends CI_Model
{
 function load_data($data)
 {
    // $this->db->order_by('urutan', 'asc');
    // $this->db->where('bulan', $data['bulan']);
    // $this->db->where('tahun', $data['tahun']);  
    // $query = $this->db->get('db_target.t_target_omzet');
    // return $query->result_array();

    $sql = "
      select  id, kode_comp, nama_comp, 
              if(bulan = 1,'jan',if(bulan = 2,'feb',if(bulan = 3,'mar',if(bulan = 4,'apr',if(bulan = 5,'mei',if(bulan=6,'jun',if(bulan=7,'jul',if(bulan=8,'agu',if(bulan=9,'sep',if(bulan=10,'okt',if(bulan=11,'nov',if(bulan=12,'des',0)))))))))))) as bulan,
              tahun,candy,herbal,(candy + herbal) as deltomed, marguna,us,natura,intrafood
      from db_target.t_target_omzet
      where tahun = $data[tahun] and bulan = $data[bulan] 
      order by urutan asc
    ";
    $query = $this->db->query($sql);

    //echo "<pre>";
    //print_r($sql);
    //echo "</pre>";
    return $query->result_array();
    
 }

 function insert($data)
 {
  $this->db->insert('testing.sample_data', $data);
 }

 function update($data, $id)
 {
  // $this->db->where('id', $id);
  // $this->db->update('testing.sample_data', $data);
  $this->db->where('id', $id);
  $this->db->update('db_target.t_target_omzet', $data);
 
}

 function delete($id)
 {
  $this->db->where('id', $id);
  $this->db->delete('testing.sample_data');
 }
}
?>