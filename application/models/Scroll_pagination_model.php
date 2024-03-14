<?php
class Scroll_pagination_model extends CI_Model
{

	

	function fetch_data($limit, $start)
	{

		$this->db->select("*");
		$this->db->from("testing.post");
		$this->db->order_by("id", "DESC");
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query;

		// $sql = "select * testing.post a order by a.id desc limit $start, $limit";
		// $query = $this->db->query($sql)->result();
		// return $query;

	}
}
?>