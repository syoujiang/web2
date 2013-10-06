<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Yijian_model extends CI_Model {

			public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function get($offset,$num)
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get('hhs_yijian',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function getOne($id)
	{
		return $this->db->get_where('hhs_yijian', array('id' => $id))->row_array();
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
		$this->db->update('hhs_yijian', $data);
	}
	public function delete($newsid)
	{
		# code...
		$this->db->query("DELETE FROM `hhs_yijian` WHERE `id` IN($newsid)");
		return true;
	}
	public function create_api($mail,$phone,$yijian)
	{
		$data = array('mail' => $mail,'phone' => $phone,'yijian' => $yijian);
		$query = $this->db->get_where('hhs_yijian', $data);
		if ($query->num_rows() > 0)
		{
			return false;
		}
		else
		{
			$data = array(
						'mail' => $mail,
						'phone' => $phone,
						'yijian' => $yijian,
						'yj_data' => date('Y-m-d H:i:s')
			);
			$this->db->insert('hhs_yijian', $data);
			return true;
		}
	}

}

/* End of file yijian_model.php */
/* Location: ./application/models/yijian_model.php */