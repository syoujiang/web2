<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fabao_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	// fabao----------------------------
	public function set_fabao()
	{
		//$type_data=$this->getOneNewsType($this->input->post('shirts'));
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
		'fbname' => $this->input->post('title'),
		'type' => $this->input->post('shirts'),
		'auth' => $this->input->post('auth'),
		'language_type' => $this->input->post('language_type'),
		'summary' => $this->input->post('zx_summary'),
		'mulu_tuijian' => $this->input->post('mulu_tuijian'),
		'mulu_summary' => $this->input->post('mulu_summary'),
		'content' => $this->input->post('text'),
		'content_phone' => $tmp ,
		'public_time' => $this->input->post('public_time'),
		'summary_url' => $this->input->post('sum_picture_id'),
		'summary_fkey' => $this->input->post('sum_picture_fkey'),
		'summary_fname' => $this->input->post('sum_picture_fname'),
		'lb_fkey' => $this->input->post('con_picture_fkey'),
		'lb_fname' => $this->input->post('con_picture_fname'),
		'lb_url' => $this->input->post('con_picture_id')
		//'fb_data' => date('Y-m-d H:i:s')
		);
		$this->db->insert('hhs_fabao', $data);
		//echo  $this->db->last_query();
		return;
	}
	public function update_fabao($id)
	{
		$tmp=$this->input->post('zx_content_phone');
		$tmp =preg_replace("/\s/","",$tmp);
		$data = array(
				'fbname' => $this->input->post('title'),
				'type' => $this->input->post('shirts'),
				'auth' => $this->input->post('auth'),
				'language_type' => $this->input->post('language_type'),
				'summary' => str_replace("\n","\\n",$this->input->post('zx_summary')),
				//'summary' => $this->input->post('zx_summary'),
				'mulu_tuijian' => str_replace("\n","\\n",$this->input->post('mulu_tuijian')),
				'mulu_summary' => str_replace("\n","\\n",$this->input->post('mulu_summary')),
				'content' => $this->input->post('text'),
				'content_phone' => $tmp ,
				'public_time' => $this->input->post('public_time'),
				'summary_url' => $this->input->post('sum_picture_id'),
				'summary_fkey' => $this->input->post('sum_picture_fkey'),
				'summary_fname' => $this->input->post('sum_picture_fname'),
				'lb_fkey' => $this->input->post('con_picture_fkey'),
				'lb_fname' => $this->input->post('con_picture_fname'),
				'lb_url' => $this->input->post('con_picture_id')
				//'fb_data' => date('Y-m-d H:i:s')
			);
		$this->db->where('id',$id);
		$this->db->update('hhs_fabao', $data);
	}
	public function Set_tuijian($id,$value)
	{
		# code...
		$data = array('tuijian' => "$value");
		$this->db->where('id',$id);
		$this->db->update('hhs_fabao', $data);
		// echo  $this->db->last_query();
	}
	public function get_fabao($offset,$num)
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get('hhs_fabao',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function get_fabao2($offset,$num,$likename)
	{
		$this->db->order_by("id", "desc");
		$this->db->like('fbname', $likename); 
		$query = $this->db->get('hhs_fabao',$num,$offset);
		echo  $this->db->last_query();
		return $query->result_array();
	}
	public function get_fabao_numer($likename)
	{
		$this->db->like('fbname', $likename);
		$this->db->from('hhs_fabao');
		return $this->db->count_all_results();
	}
	public function delete_fabao($newsid)
	{
		$this->db->query("DELETE FROM `hhs_fabao` WHERE `id` IN($newsid)");
		$this->delete_lunbo($newsid);
		return true;
		# code...
	}
	public function delete_order($newsid)
	{
		$this->db->query("DELETE FROM `hhs_order` WHERE `order_id` IN($newsid)");
		// $this->delete_lunbo($newsid);
		return true;
		# code...
	}
	function getOneFabao($id)
	{
		return $this->db->get_where('hhs_fabao', array('id' => $id))->row_array();
	}
	public function getFbName($id)
	{
		$fbname="";
		$this->db->select('fbname');
		$query = $this->db->get_where('hhs_fabao', array('id' => $id));
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(); 
			$fbname = $row['fbname'];
		}

		return $fbname;
		# code...
	}
	public function getFbID($name)
	{
		$id="";
		$this->db->select('id');
		$query = $this->db->get_where('hhs_fabao', array('fbname' => $name));
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(); 
			$id = $row['id'];
		}

		return $id;
		# code...
	}
	//tuijian 法宝
	public function get_tuijian($type)
	{
		//$this->db->select('id, fbname,summary_url,summary_fkey,summary_fname');
		$this->db->select('id, type,fbname, auth,summary,summary_url,summary_fkey,summary_fname,kucun');
		$query = $this->db->get_where('hhs_fabao', array('type' => $type,'tuijian' => '1'));
		return $query->result_array();
	}
	public function get_tuijian_type()
	{
		$query = $this->db->query("SELECT * FROM hhs_fabao_type WHERE id in(SELECT type FROM hhs_fabao WHERE tuijian=1)");
		//echo  $this->db->last_query();
		return $query->result_array();
	}
	//lunbotu------------------------------------
	public function get_fabao_no_lunbo()
	{
		# code...
		$query = $this->db->query("select id,fbname from hhs_fabao where id not in (select fabao_id from hhs_lunbo)");
		return $query->result_array();
	}
	public function get_lunbo($offset,$limit)
	{
		$this->db->order_by("weight", "asc");
		$query = $this->db->get('hhs_lunbo',$limit,$offset);
		return $query->result_array();
	}
	public function get_lunbo_api()
	{
		$this->db->order_by("weight", "asc");
		$query = $this->db->query("select id,fbname,lb_fkey,lb_fname from hhs_fabao where id in (select fabao_id from hhs_lunbo)");
		return $query->result_array();
	}
	public function add_lunbo($value='')
	{
		$slug = url_title($this->input->post('lunbotu'), 'dash', TRUE);
		$weight = $this->db->count_all_results('hhs_lunbo');
		$lbname= $this->input->post('lunbotu');
		if($weight <5)
		{
			if($this->input->post('lunbotu') >0)
			{
				$data = array('fabao_id' => $this->input->post('lunbotu'),
				'weight' => ($weight+1)
				);
				$this->db->insert('hhs_lunbo', $data);
				return true;
			}
			else
				return false;
		}
		else
		{
			return false;
		}
	}
	public function delete_lunbo($newsid)
	{
		# code...
		$this->db->query("DELETE FROM `hhs_lunbo` WHERE `fabao_id` IN($newsid)");
		return true;
	}
	// fabaotype------------------------
	public function get_fabao_type($offset,$limit)
	{
		$query = $this->db->get('hhs_fabao_type',$limit,$offset);
		return $query->result_array();
	}
	public function get_all_fabao_type()
	{
		$query = $this->db->get('hhs_fabao_type');
		return $query->result_array();
	}
	public function set_fabao_type()
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		$weight = $this->db->count_all_results('hhs_fabao_type');
		$data = array(
		'fabao_type' => $this->input->post('title')
		);

		return $this->db->insert('hhs_fabao_type', $data);
	}
	public function getOneFabaoType($id)
	{
		return $this->db->get_where('hhs_fabao_type', array('id' => $id))->row_array();
	}
	
	public function update_fabao_type($id)
	{
		$slug = url_title($this->input->post('title'), 'dash', TRUE);

		$data = array(
		'fabao_type' => $this->input->post('title')
		);
		$this->db->where('id',$id);
		$this->db->update('hhs_fabao_type', $data);
	}
	public function delete_fabao_types($newsid)
	{
		//删除数据
		$this->db->query("DELETE FROM `hhs_fabao_type` WHERE `id` IN($newsid)");
		return true;
		# code...
	}
	// api
	public function get_fabao_limit_api($type_id,$id,$direct,$limit)
	{
		if($direct == 'down')
		{
			$this->db->where('id <', $id); 
			$this->db->order_by("id", "desc");
			$this->db->select('id, type,fbname, auth,summary,summary_url,summary_fkey,summary_fname,kucun');
			$query = $this->db->get_where('hhs_fabao',array('type' => $type_id),$limit);
			return $query->result_array();
		}
		elseif ($direct == 'up') 
		{
			$this->db->where('id >', $id);
			$uprow=$this->db->count_all_results('hhs_fabao');
			if($uprow > 10)
			{
				$offset=$uprow-10;
				$sql = "Select id, type,fbname, auth,summary,summary_url,summary_fkey,summary_fname,kucun,".
				"(@rowNum:=@rowNum+1) as rowNo From hhs_fabao,(Select (@rowNum :=0) ) b ".
				"where hhs_fabao.id>'$id' Order by hhs_fabao.id Desc LIMIT $offset,10";
				$query = $this->db->query($sql);
				return $query->result_array();
			}
			elseif ($uprow <= 10 && $uprow > 0) 
			{
				$this->db->where('id >', $id); 
				$this->db->order_by("id", "desc");
				$this->db->select('id, type,fbname, auth,summary,summary_url,summary_fkey,summary_fname,kucun');
				$query = $this->db->get_where('hhs_fabao',array('type' => $type_id),$limit);
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
			$this->db->order_by("id", "desc");
			$this->db->select('id, type,fbname, auth,summary,summary_url,summary_fkey,summary_fname,kucun');
			$query = $this->db->get_where('hhs_fabao',array('type' => $type_id),$limit);
			return $query->result_array();
		}
		return;
	}
	public function get_fabao_search($likename)
	{
		$this->db->select('id, fbname, auth,summary,summary_fkey,summary_fname,kucun');
		$this->db->like('fbname', $likename);
		$query = $this->db->get_where('hhs_fabao');
		return $query->result_array();
	}
	public function get_fabao_all_api($type_id)
	{
		$this->db->select('id, type,fbname, auth,summary,summary_url,summary_fkey,summary_fname,kucun');
		$query = $this->db->get_where('hhs_fabao',array('type' => $type_id));
		return $query->result_array();
	}

	function getOneFabao_api($id)
	{
		$this->db->select('id, fbname, type,auth,language_type,summary,public_time,mulu_summary,mulu_tuijian,content_phone,summary_url,summary_fkey,summary_fname,kucun');
		return $this->db->get_where('hhs_fabao', array('id' => $id))->row_array();
	}
	// 法宝添加到购物车里
	public function AddFabao_api($user_name,$fabaoid,$number)
	{
		# code...
		//购物车是否存在
			# code...
		$data = array('user_name' => $user_name,'fabaoid' => $fabaoid);
		$this->db->select('number');
		$query = $this->db->get_where('hhs_shopping', $data)->result_array();
		$count=0;
		//echo  $this->db->last_query();
		foreach ($query as $key) {
			# code...
			$count = $key['number'];
		}
		if($count > 0) 
		{
			$allcount=$count+$number;
			$fabaonumber = array('number' => $allcount);
			$this->db->update('hhs_shopping', $fabaonumber,$data);
			return true;
		}
		//不存在
		else
		{
			$order_data = array('user_name' => $user_name,
							'fabaoid' => $fabaoid,
							'name' => $this->getFbName($fabaoid),
							'number' => $number);
			$this->db->insert('hhs_shopping', $order_data);
			return true;
		}
	}
	public function UpdateFabao_api($user_name,$fabaoid,$number)
	{
		# code...
		$data = array('user_name' => $user_name,'fabaoid' => $fabaoid);
		$this->db->select('number');
		$query = $this->db->get_where('hhs_shopping', $data)->result_array();
		$count=0;
		//echo  $this->db->last_query();
		foreach ($query as $key) {
			# code...
			$count = $key['number'];
		}
		if($count > 0) 
		{
			# code...
			$fabaonumber = array('number' => $number);
			$this->db->update('hhs_shopping', $fabaonumber,$data);
			return true;
		}
		else{
			return false;
		}
	}
	public function GetFabao_api($user_name)
	{
		# code...
		$this->db->select('user_name, fabaoid,name,number');
		$query = $this->db->get_where('hhs_shopping', array('user_name' => $user_name));
		//echo  $this->db->last_query();
		return $query->result_array();
	}
	public function get_order($offset,$num)
	{
		$this->db->order_by("order_time", "desc");
		$query = $this->db->get('hhs_order',$num,$offset);
		return $query->result_array();
	}
	public function get_order_by_id($id)
	{
		$query = $this->db->get_where('hhs_order',array('order_id' => $id));
		return $query->row_array();
	}
	public function get_one_order($id)
	{
		$query = $this->db->get_where('hhs_order_info',array('order_id' => $id));
		return $query->result_array();
	}
	public function delete_shop($user,$id)
	{
		$this->db->delete('hhs_shopping',array('user_name' => $user,'fabaoid' => $id));
	}
	public function empty_shop($user)
	{
		$this->db->delete('hhs_shopping',array('user_name' => '$user'));
		log_message('error',$this->db->last_query());
	}
	public function create_order($user, $fabao,&$errmsg)
	{
		if(count($fabao)==0)
		{
			$errmsg = "不能提交空的订单";
			return false;
		}
				// create order
		$order_data = array('name' => $user,
				'status' => '0',
				'order_time' => date('Y-m-d H:i:s'));
		$this->db->insert('hhs_order', $order_data);
		$order_id = $this->db->insert_id();
		//遍历法宝
		foreach ($fabao as $key => $value) {
			# code...
			log_message('error',$value['fabaoid']);
			log_message('error',$value['number']);
		    $order_info = array('order_id' => $order_id,
								'fabao_id' => $value['fabaoid'],
								'number' => $value['number']);
			$this->db->insert('hhs_order_info', $order_info);
			$this->delete_shop($user,$value['fabaoid']);
		}

		$errmsg = "提交成功";
		return true;

	}
	public function update_order($order_id)
	{
		$order_data = array('status' => $this->input->post('shirts'));
		$this->db->update('hhs_order',$order_data,array('order_id' =>$order_id));
		// echo  $this->db->last_query();
	}
	public function get_order_by_mail_api($mail)
	{
		$this->db->order_by("order_time", "desc");
		$query = $this->db->get_where('hhs_order',array('name' => $mail));
		$sendmsg = array();
		$i=0;
		foreach ($query->result_array() as $row)
		{
			
			log_message('debug','message '.$row['order_id']);
			log_message('debug','message '.$row['status']);
			log_message('debug','message '.$row['order_time']);
			$content=$this->get_one_order($row['order_id']);

			$content_count = count($content);
			log_message('debug','message '.$row['order_id'].' count is '.$content_count);
			$sendmsg[$i]['order_id']=$row['order_id'];

			for ($j=0; $j <$content_count ; $j++) { 
				log_message('debug','fabao_id '.$content[$j]['fabao_id']);
				log_message('debug','number '.$content[$j]['number']);
				$sendmsg[$i][$j]['fabao_name']=$this->getFbName($content[$j]['fabao_id']);
				$sendmsg[$i][$j]['fabao_summary']=$this->getFbName($content[$j]['fabao_id']);
				$sendmsg[$i][$j]['fabao_num']=$content[$j]['number'];
			}
			$sendmsg[$i]['order_time']=$row['order_time'];
			$i++;
		}
		return $sendmsg;
	}
}

/* End of file fabao_model.php */
/* Location: ./application/models/fabao_model.php */