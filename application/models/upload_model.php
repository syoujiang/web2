<?php
/**
* 
*/
class upload_model extends CI_Model
{
	function __construct(){
		$this->load->database();
	}

	public function geturl($id)
	{
		return $this->db->get_where('uploads', array('id' => $id))->row_array();
	}
}
?>