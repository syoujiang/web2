<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Huodong_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function create()
	{
		log_message('error','huodong_pic. '.$this->input->post('huodong_pic'));
		$arraypic = explode(',', $this->input->post('huodong_pic'));	
		$tmp=$this->input->post('mingxi_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$tmp2=$this->input->post('gongde_phone');
		$tmp2 =preg_replace("/\s/","",$tmp2);
		$data = array(
		'gg_id' => $this->input->post('shirts'),
		'jijin' => $this->input->post('jijin'),
		'mingxi' => $this->input->post('text'),
		'mingxi_phone' => $tmp,
		'mingxi_url' => $this->input->post('sum_picture_id'),
		'mingxi_fkey' => $this->input->post('sum_picture_fkey'),
		'mingxi_fname' => $this->input->post('sum_picture_fname'),

		'gongde' => $this->input->post('text2'),
		'gongde_phone' => $tmp2,
		'gongde_url' => $this->input->post('con_picture_id'),
		'gongde_fkey' => $this->input->post('con_picture_fkey'),
		'gongde_fname' => $this->input->post('con_picture_fname')
		);
		$this->db->insert('hhs_hongdong', $data);
		$lastInsertId = $this->db->insert_id();
		foreach($arraypic as $value){ 
			$this->addPic($lastInsertId,$value);
		}
		// echo  $this->db->last_query();
		return;
	}
	public function addPic($id,$fkey)
	{
		$data = array('id'=>$id,'file_key' =>$fkey);
		$query=$this->db->get_where('hhs_huodong_pic', $data);
		if ($query->num_rows() == 0)
		{	$this->db->insert('hhs_huodong_pic', $data);
		}
	}
	public function updatePic($id,$key)
	{
		$data = array('id' => $id);
		$this->db->where('file_key',$key);
		$this->db->update('hhs_huodong_pic', $data);
		log_message('error','message'.$this->db->last_query());
		# code...
	}
	public function getPic($id)
	{
		# code...
		$this->db->select('id,file_key,file_name');
		$query = $this->db->get_where('hhs_huodong_pic', array('id' => $id));
		return $query->result_array();
	}
	public function delPic($id)
	{
		$this->db->query("DELETE FROM `hhs_huodong_pic` WHERE `file_key` ='$id'");
		log_message('error','message111111'.$this->db->last_query());
		return true;
	}
	public function get($offset,$num)
	{
		$query = $this->db->query("select * from hhs_gonggao where id in(select gg_id from hhs_hongdong) order by id desc LIMIT $offset,$num");
		//$query = $this->db->query("select * from hhs_hongdong order by id desc LIMIT $offset,$num");
		return $query->result_array();
	}
	public function get2()
	{
		$query = $this->db->query("select * from hhs_gonggao where id not in(select gg_id from hhs_hongdong)");
		return $query->result_array();
		# code...
	}
	public function get3($id)
	{
		$query = $this->db->query("select * from hhs_gonggao where id not in(select gg_id from hhs_hongdong where gg_id!=$id)");
		return $query->result_array();
		# code...
	}
	public function getOne($id)
	{
		$query = $this->db->get_where('hhs_hongdong', array('gg_id' => $id));
	//	echo  $this->db->last_query();
		return $query->row_array();
	}
	public function update($id)
	{
		$tmp=$this->input->post('mingxi_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$tmp2=$this->input->post('gongde_phone');
		$tmp2 =preg_replace("/\s/","",$tmp2);
		$data = array(
		'gg_id' => $this->input->post('shirts'),
		'jijin' => $this->input->post('jijin'),
		'mingxi' => $this->input->post('text'),
		'mingxi_phone' => $tmp,
		'mingxi_url' => $this->input->post('sum_picture_id'),
		'mingxi_fkey' => $this->input->post('sum_picture_fkey'),
		'mingxi_fname' => $this->input->post('sum_picture_fname'),

		'gongde' => $this->input->post('text2'),
		'gongde_phone' => $tmp2,
		'gongde_url' => $this->input->post('con_picture_id'),
		'gongde_fkey' => $this->input->post('con_picture_fkey'),
		'gongde_fname' => $this->input->post('con_picture_fname')
		);
		$this->db->where('id',$id);
		$this->db->update('hhs_hongdong', $data);
		$arraypic = explode(',', $this->input->post('huodong_pic'));
		foreach($arraypic as $value){ 
			$this->addPic($id,$value);
		}
	}
	public function delete($newsid)
	{
		# code...
		$this->db->query("DELETE FROM `hhs_hongdong` WHERE `id` IN($newsid)");
		return true;
	}
	//api
	public function get_api()
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->query("select id,title,gg_date from hhs_gonggao where id in(select gg_id from hhs_hongdong)");
		return $query->result_array();
	}
	public function getOne_api($id)
	{
		$query = $this->db->get_where('hhs_hongdong', array('gg_id' => $id));
		return $query->row_array();
	}
}

/* End of file huodong_model.php */
/* Location: ./application/models/huodong_model.php */