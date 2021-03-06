<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fangsheng_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function create()
	{
		//$type_data=$this->getOneNewsType($this->input->post('shirts'));
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
		$this->db->insert('hhs_fangsheng', $data);
	//	echo  $this->db->last_query();
		return;
	}
	public function get($offset,$num)
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get('hhs_fangsheng',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function getOne($id)
	{
		return $this->db->get_where('hhs_fangsheng', array('id' => $id))->row_array();
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
		$this->db->update('hhs_fangsheng', $data);
	}
	public function delete($newsid)
	{
		# code...
		$this->db->query("DELETE FROM `hhs_fangsheng` WHERE `id` IN($newsid)");
		return true;
	}
	// api
	public function get_summary_api()
	{
		# code...
		$this->db->order_by("id", "desc");
		$this->db->select('id, title,summary_url,summary_fkey,summary_fname,summary,kaishi');
		$query = $this->db->get_where('hhs_fangsheng');
		//echo  $this->db->last_query();
		return $query->result_array();
	}
	public function getOne_api($id)
	{
		$this->db->select('id,title,con_url,con_fkey,con_fname,summary,kaishi,kaishi_time,content_phone');
		return $this->db->get_where('hhs_fangsheng', array('id' => $id))->row_array();
	}
        
        
        //添加放生到收藏中
        public function add_fangsheng_to_collect($mail,$id)
	{
		$data = array(
			'mail' => $mail,
			'id' => $id,
                        'type' => 1
			);

		$query = $this->db->get_where('hhs_news_collect',$data);
		if($query->num_rows()==0)
		{
			$this->db->insert('hhs_news_collect', $data);
		}
		log_message('debug','num_rows'.$query->num_rows());
		return true;
	}
	public function delete_fangsheng_from_collect($mail,$id)
	{
		$this->db->delete('hhs_news_collect',array('mail' => $mail,'id' => $id,'type'=> 1));
	}
        public function check_fangsheng_to_collect($token,$id)
	{
            $query = $this->db->get_where('hhs_users_token', array('token' => $token));
            foreach ($query->result_array() as $row)
            {
                $mail= $row['mail'];
            }
            if($mail == null)
            {
                return false;
            }
            else
            {
		$data = array(
			'mail' => $mail,
			'id' => $id,
                        'type' => 1
			);

		$query = $this->db->get_where('hhs_news_collect',$data);
		if($query->num_rows()==0)
		{
                    return false;
		}
                else 
                {
                    return true;
                }

            }
	}
}

/* End of file fabao_model.php */
/* Location: ./application/models/fabao_model.php */