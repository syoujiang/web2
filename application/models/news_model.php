<?php
/**
* 
*/
class news_model extends CI_Model
{
	function __construct(){
		$this->load->database();
		$this->load->helper('url');
	}

	public function set_news()
	{
		//$type_data=$this->getOneNewsType($this->input->post('shirts'));
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
		'zx_title' => $this->input->post('title'),
		'zx_type' => $this->input->post('shirts'),
		'zx_summary' => $this->input->post('zx_summary'),
		'zx_content' => $this->input->post('text'),
		'zx_content_phone' => $tmp ,
		'zx_create' => $this->input->post('zx_create'),
		'zx_from' => $this->input->post('zx_from'),
		'summary_url' => $this->input->post('sum_picture_id'),
		'summary_fkey' => $this->input->post('sum_picture_fkey'),
		'summary_fname' => $this->input->post('sum_picture_fname'),
		'content_fkey' => $this->input->post('con_picture_fkey'),
		'content_fname' => $this->input->post('con_picture_fname'),
		'content_url' => $this->input->post('con_picture_id'),
		'zx_date' => date('Y-m-d H:i:s')
		);
		return $this->db->insert('hhs_news', $data);
	}
	public function get_news($offset,$num)
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get('hhs_news',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function get_news_limit_api($type_id,$id,$direct,$limit)
	{
		$now_data=date('Y-m-d H:i:s',time());
		if($direct == 'down')
		{
			$this->db->where('id <', $id); 
			$this->db->order_by("id", "desc");
			$this->db->select('id, zx_title, zx_type,zx_summary,summary_url,summary_fkey,summary_fname');
			$query = $this->db->get_where('hhs_news',array('zx_type' => $type_id),$limit);
			return $query->result_array();
		}
		elseif ($direct == 'up') 
		{
			$this->db->where('id >', $id);
			$uprow=$this->db->count_all_results('hhs_news');
			if($uprow > 10)
			{
				$offset=$uprow-10;
				$sql = "Select id, zx_title, zx_type,zx_summary,summary_url,summary_fkey,summary_fname,".
				"(@rowNum:=@rowNum+1) as rowNo From hhs_news,(Select (@rowNum :=0) ) b ".
				"where hhs_news.id>'$id' and zx_create < '$now_data' Order by hhs_news.id Desc LIMIT $offset,10";
				$query = $this->db->query($sql);
				return $query->result_array();
			}
			elseif ($uprow <= 10 && $uprow > 0) 
			{
				$this->db->where('zx_create <',$now_data);
				$this->db->where('id >', $id); 
				$this->db->order_by("id", "desc");
				$this->db->select('id, zx_title, zx_type,zx_summary,summary_url,summary_fkey,summary_fname');
				$query = $this->db->get_where('hhs_news',array('zx_type' => $type_id),$limit);
				//echo  $this->db->last_query();
				return $query->result_array();
			}
			else
			{
				return 0;
			}	
		}
		else
		{
			$this->db->where('zx_create <',$now_data);
			$this->db->order_by("id", "desc");
			$this->db->select('id, zx_title, zx_type,zx_summary,summary_url,summary_fkey,summary_fname');
			$query = $this->db->get_where('hhs_news',array('zx_type' => $type_id),$limit);
			return $query->result_array();
		}
		return;
	}
	public function get_sql_time($table,$id,$key)
	{
		$sqltime="";
		if($id > 0)
		{
			$this->db->select($key);
			$content=$this->db->get_where($table,array('id' => $id))->row_array();
			if (isset($content[$key]))
			{
				$sqltime = $content[$key];
			}
			# code...
			
		}
		return $sqltime;

	}
	function getOneNews($id)
	{
		return $this->db->get_where('hhs_news', array('id' => $id))->row_array();
	}
	function getOneNews_api($id)
	{
		$this->db->select('id, zx_title, zx_type,zx_content_phone,content_url,content_fkey,content_fname,zx_from,zx_date');
		return $this->db->get_where('hhs_news', array('id' => $id))->row_array();
	}
	public function delete_news($newsid)
	{
		return $this->db->query("DELETE FROM `hhs_news` WHERE `id` IN($newsid)");
		# code...
	}
	public function delete_news_fkey($id,$type)
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		if($type == 0)
		{
			$data = array(
			'summary_fkey' => ""
			);
		}
		else
		{		
			$data = array(
			'content_fkey' => "");
		}

		$this->db->where('id',$id);
		$this->db->update('hhs_news', $data);
		log_message('error','message '.$this->db->last_query());
	}
	public function update_news($id)
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
			'zx_title' => $this->input->post('title'),
			'zx_type' => $this->input->post('shirts'),
			'zx_summary' => $this->input->post('zx_summary'),
			'zx_content' => $this->input->post('text'),
			'zx_content_phone' => $tmp,
			'zx_create' => $this->input->post('zx_create'),
			'zx_from' => $this->input->post('zx_from'),
			'summary_url' => $this->input->post('sum_picture_id'),
			'summary_fkey' => $this->input->post('sum_picture_fkey'),
			'summary_fname' => $this->input->post('sum_picture_fname'),
			'content_fkey' => $this->input->post('con_picture_fkey'),
			'content_fname' => $this->input->post('con_picture_fname'),
			'content_url' => $this->input->post('con_picture_id'),
			'zx_date' => date('Y-m-d H:i:s')
			);
		$this->db->where('id',$id);
		$this->db->update('hhs_news', $data);
	}

	function getOneNewsType($id)
	{
		return $this->db->get_where('hhs_news_type', array('id' => $id))->row_array();
	}
	public function get_news_type($offset,$limit)
	{
		$this->db->order_by("weight", "asc");
		$query = $this->db->get('hhs_news_type',$limit,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function get_all_news_type()
	{
		$this->db->order_by("weight", "asc");
		$query = $this->db->get('hhs_news_type');
		return $query->result_array();
	}
	public function get_all_news_type_api()
	{
		$this->db->order_by("weight", "asc");
		$this->db->select('id,news_type');
		$query = $this->db->get('hhs_news_type');
		return $query->result_array();
	}
	public function set_news_type()
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		$weight = $this->db->count_all_results('hhs_news_type');
		$data = array(
		'news_type' => $this->input->post('title'),
		'weight' => ($weight+1)
		);

		return $this->db->insert('hhs_news_type', $data);
	}
	public function update_news_type($id)
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);

		$data = array(
		'news_type' => $this->input->post('title')
		);
		$this->db->where('id',$id);
		$this->db->update('hhs_news_type', $data);
	}
	public function delete_news_types($newsid)
	{
		//删除数据
		$this->db->query("DELETE FROM `hhs_news_type` WHERE `id` IN($newsid)");

		//更新整个weight
		$testsql="Select id,`weight`,(@rowNum:=@rowNum+1) as rowNo From hhs_news_type, (Select (@rowNum :=0) ) b ".
		"Order by hhs_news_type.`weight` asc";
		$myquery = $this->db->query($testsql);
		foreach ($myquery->result() as $row)
		{
			$data = array(
			'weight' => $row->rowNo
			);
			$this->db->where('id', $row->id);
			$this->db->update('hhs_news_type', $data); 
		}
		return true;
		# code...
	}
	public function get_news_by_mail_api($mail)
	{
		$query = $this->db->get_where('hhs_news_collect',array('mail' => $mail));
		$sendmsg = array();
		$i=0;
		foreach ($query->result_array() as $row)
		{
			log_message('debug','get_news_by_mail_api '.$row['id']);
			$sendmsg[$i]['news_id']=$row['id'];
			
			$this->db->select('zx_title, zx_summary,summary_fkey,summary_fname');
			$query2=$this->db->get_where('hhs_news', array('id' => $row['id']));
			if ($query2->num_rows() > 0)
			{
				$row2 = $query2->row_array(); 
				$sendmsg[$i]['news_title']=$row2['zx_title'];
				$sendmsg[$i]['news_summary']=$row2['zx_summary'];
				$sendmsg[$i]['news_summary_fkey']=$row2['summary_fkey'];
				$sendmsg[$i]['news_summary_fname']=$row2['summary_fname'];
			}
			$i++;
		}
		return $sendmsg;
	}
	public function add_news_to_collect($mail,$id)
	{
		$data = array(
			'mail' => $mail,
			'id' => $id
			);

		$query = $this->db->get_where('hhs_news_collect',$data);
		if($query->num_rows()==0)
		{
			$this->db->insert('hhs_news_collect', $data);
		}
		log_message('debug','num_rows'.$query->num_rows());
		return true;
	}
	public function delete_news_from_collect($mail,$id)
	{
		$this->db->delete('hhs_news_collect',array('mail' => $mail,'id' => $id));
	}
}
?>