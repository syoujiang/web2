<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Yigong_model extends CI_Model {

		public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function get($offset,$num)
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get('hhs_yigong',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function get2()
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get_where('hhs_yigong');
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function getOne($id)
	{
		return $this->db->get_where('hhs_yigong', array('id' => $id))->row_array();
	}
	public function update($id)
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
		'title' => $this->input->post('title'),
		'kaishi' => $this->input->post('auth'),
		'kaishi_time' => $this->input->post('public_time'),
		'summary' => $this->input->post('zx_summary'),
		'summary_url' => $this->input->post('sum_picture_id'),
		'summary_fkey' => $this->input->post('sum_picture_fkey'),
		'summary_fname' => $this->input->post('sum_picture_fname'),
		
		'content' => $this->input->post('text'),
		'content_phone' => $tmp ,

		'con_fkey' => $this->input->post('con_picture_fkey'),
		'con_fname' => $this->input->post('con_picture_fname'),
		'con_url' => $this->input->post('con_picture_id'),
		'fs_data' => date('Y-m-d H:i:s')
		);
		$this->db->where('id',$id);
		$this->db->update('hhs_yigong', $data);
	}
	public function delete($newsid)
	{
		# code...
		$this->db->query("DELETE FROM `hhs_yigong` WHERE `id` IN($newsid)");
		return true;
	}
	public function create_api($name,$phone,$mail,$qq)
	{
		$this->db->select('field1, field2');
		$data = array(
		'name' => $name,
		'phone' => $phone,
		'mail' => $mail,
		'qq' => $qq,
		'yg_data' => date('Y-m-d H:i:s')
		);
		$this->db->insert('hhs_yigong', $data);
		return ture;
	}

}

/* End of file other_model.php */
/* Location: ./application/models/other_model.php */