<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Yunfei_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	// fabaotype------------------------
	public function get()
	{
		$query = $this->db->get('hhs_fabao_yufei');
		return $query->row_array();
	}
	public function update()
	{
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
		'info' => $this->input->post('text'),
		'info_iphone' => $tmp
		);
		$this->db->update('hhs_fabao_yufei', $data);
	}
	public function delete($newsid)
	{
		//删除数据
		$this->db->query("DELETE FROM `hhs_fabao_yufei` WHERE `id` IN($newsid)");
		return true;
		# code...
	}
	public function get_api()
	{
		$this->db->select('info_iphone');
		$query = $this->db->get('hhs_fabao_yufei');
		return $query->row_array();
	}
}

/* End of file fabao_model.php */
/* Location: ./application/models/fabao_model.php */