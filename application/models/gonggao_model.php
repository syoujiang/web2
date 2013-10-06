<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gonggao_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function create()
	{
		$data = array(
		'title' => $this->input->post('title'),
		'gg_date' => $this->input->post('gg_date'),
		'didian' => $this->input->post('didian'),
		'x_site' => $this->input->post('x_site'),
		'y_site' => $this->input->post('y_site'),
		'lianluo' => $this->input->post('lianluo'),
		'telephone' => $this->input->post('telephone'),
		
		'mail' => $this->input->post('mail'),

		'qq' => $this->input->post('qq'),
		'zhuchi' => $this->input->post('zhuchi'),
		'renyuan' => $this->input->post('renyuan'),
		'c_date' => date('Y-m-d H:i:s'),
		'gg_push' => $this->input->post('zx_create')
		);
		$this->db->insert('hhs_gonggao', $data);
		// echo  $this->db->last_query();
		return;
	}
	public function get($offset,$num)
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get('hhs_gonggao',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function get2()
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get_where('hhs_gonggao');
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function getOne($id)
	{
		return $this->db->get_where('hhs_gonggao', array('id' => $id))->row_array();
	}
	public function update($id)
	{
		$data = array(
		'title' => $this->input->post('title'),
		'gg_date' => $this->input->post('gg_date'),
		'didian' => $this->input->post('didian'),
		'x_site' => $this->input->post('x_site'),
		'y_site' => $this->input->post('y_site'),
		'lianluo' => $this->input->post('lianluo'),
		'telephone' => $this->input->post('telephone'),
		
		'mail' => $this->input->post('mail'),

		'qq' => $this->input->post('qq'),
		'zhuchi' => $this->input->post('zhuchi'),
		'renyuan' => $this->input->post('renyuan'),
		'c_date' => date('Y-m-d H:i:s'),
		'gg_push' => $this->input->post('zx_create')
		);
		$this->db->where('id',$id);
		$this->db->update('hhs_gonggao', $data);
	}
	public function delete($newsid)
	{
		# code...
		$this->db->query("DELETE FROM `hhs_gonggao` WHERE `id` IN($newsid)");
		return true;
	}
// api
	public function get_lunbo_api()
	{
		# code...
		$this->db->select('id,title');
		$query = $this->db->get('hhs_gonggao');
		return $query->result_array();
	}
	public function getOne_api($id)
	{
		$this->db->select('id,title,gg_date,x_site,y_site,jihe_date,didian,lianluo,telephone,mail,qq,zhuchi,renyuan');
		$query = $this->db->get_where('hhs_gonggao', array('id' => $id));
		return $query->row_array();
	}
}

/* End of file gonggao_model.php */
/* Location: ./application/models/gonggao_model.php */