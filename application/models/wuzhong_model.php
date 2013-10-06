<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wuzhong_model extends CI_Model {
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
		'xueming' => $this->input->post('xueming'),
		'quyu' => $this->input->post('quyu'),
		'gaishu' => $this->input->post('gaishu'),
		'summary_url' => $this->input->post('sum_picture_id'),
		'summary_fkey' => $this->input->post('sum_picture_fkey'),
		'summary_fname' => $this->input->post('sum_picture_fname'),
		'tezheng' => $this->input->post('tezheng'),
		'xixing' => $this->input->post('text'),
		'xixing_phone' => $tmp ,
		'fangshengdidian' => $this->input->post('fangshengdidian'),
		'jiage' => $this->input->post('jiage'),
		'con_fkey' => $this->input->post('con_picture_fkey'),
		'con_fname' => $this->input->post('con_picture_fname'),
		'con_url' => $this->input->post('con_picture_id')
		);
		$this->db->insert('hhs_fangsheng_wuzhong', $data);
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
		$query = $this->db->get('hhs_fangsheng_wuzhong',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function getOne($id)
	{
		return $this->db->get_where('hhs_fangsheng_wuzhong', array('id' => $id))->row_array();
	}
	public function update($id)
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
		'name' => $this->input->post('title'),
		'xueming' => $this->input->post('xueming'),
		'quyu' => $this->input->post('quyu'),
		'gaishu' => $this->input->post('gaishu'),
		'summary_url' => $this->input->post('sum_picture_id'),
		'summary_fkey' => $this->input->post('sum_picture_fkey'),
		'summary_fname' => $this->input->post('sum_picture_fname'),
		'tezheng' => $this->input->post('tezheng'),
		'xixing' => $this->input->post('text'),
		'xixing_phone' => $tmp ,
		'fangshengdidian' => $this->input->post('fangshengdidian'),
		'jiage' => $this->input->post('jiage'),
		'con_fkey' => $this->input->post('con_picture_fkey'),
		'con_fname' => $this->input->post('con_picture_fname'),
		'con_url' => $this->input->post('con_picture_id')
		);
		$this->db->where('id',$id);
		$this->db->update('hhs_fangsheng_wuzhong', $data);
	}
	public function delete($newsid)
	{
		# code...
		$this->db->query("DELETE FROM `hhs_fangsheng_wuzhong` WHERE `id` IN($newsid)");
		return true;
	}

	// api
	public function get_type_api()
	{
		$this->db->select('id,name,summary_url,summary_fname,summary_fkey');
		$query = $this->db->get_where('hhs_fangsheng_wuzhong');
		return $query->result_array();
	}
	public function getOne_api($id)
	{
		$this->db->select('id,name,xueming,quyu,xixing_phone,gaishu,tezheng,fangshengdidian,jiage,con_url,con_fname,con_fkey');
		$query = $this->db->get_where('hhs_fangsheng_wuzhong', array('id' => $id));
		return $query->row_array();
	}
}

/* End of file fabao_model.php */
/* Location: ./application/models/fabao_model.php */