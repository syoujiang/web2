<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xinjing_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function create()
	{
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
		'name' => $this->input->post('title'),
		'type' => $this->input->post('shirts'),
		'file_key' => $this->input->post('sum_picture_fkey'),
		'file_name' => $this->input->post('sum_picture_fname'),
		'info' => $tmp
		);
		$this->db->insert('hhs_xinjing', $data);
		//echo  $this->db->last_query();
		return;
	}
	public function show()
	{
		$id=$this->uri->segment(3);
		$data=$this->Wuzhong_model->getOne($id);
		$data['base'] = $this->config->item('base_url');
		$this->load->view('templates/head', $data);
		$this->load->view('wuzhong/show', $data); 
		$this->load->view('templates/footer'); 

	}
	public function get($offset,$num)
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get('hhs_xinjing',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function getOne($id)
	{
		return $this->db->get_where('hhs_xinjing', array('id' => $id))->row_array();
	}
	public function update($id)
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
		'name' => $this->input->post('title'),
		'type' => $this->input->post('shirts'),
		'file_key' => $this->input->post('sum_picture_fkey'),
		'file_name' => $this->input->post('sum_picture_fname'),
		'info' => $tmp
		);
		$this->db->where('id',$id);
		$this->db->update('hhs_xinjing', $data);
	}
	public function delete($newsid)
	{
		$this->db->query("DELETE FROM `hhs_xinjing` WHERE `id` IN($newsid)");
		return true;
	}

	// api
	public function get_type_api()
	{
		$this->db->select('id,name,summary_url,summary_fname,summary_fkey');
		$query = $this->db->get_where('hhs_xinjing');
		return $query->result_array();
	}
	public function getOne_api($id)
	{
		$this->db->select('id,name,xueming,quyu,xixing_phone,gaishu,tezheng,fangshengdidian,jiage,con_url,con_fname,con_fkey');
		$query = $this->db->get_where('hhs_xinjing', array('id' => $id));
		return $query->row_array();
	}
}

/* End of file xinjing_model.php */
/* Location: ./application/models/xinjing_model.php */