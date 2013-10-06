<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fotang_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('date');
	}
	public function get($offset,$num)
	{
		$this->db->order_by("id", "desc");
		$query = $this->db->get('hhs_gongke',$num,$offset);
	//	echo  $this->db->last_query();
		return $query->result_array();
	}
	public function get_sum($query)
	{
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if($row['sum(number)'])
				{
					return $row['sum(number)'];
				}
				else
				{	
					return 0;
				}
			}
		}
		return 0;
	}
	public function get_today_api($user,$type)
	{
		$query = $this->db->query("select sum(number) from hhs_gongke where name='$user' and gongke_type='$type' and TO_DAYS(riqi)=TO_DAYS(now())");
		return $this->get_sum($query);
	}
	public function get_month_api($user,$type)
	{
		// select * from ht_invoice_information where MONTH(create_date)=MONTH(NOW()) and year(create_date)=year(now());
		$query = $this->db->query("select sum(number) from hhs_gongke where name='$user' and gongke_type='$type' and MONTH(riqi)=MONTH(NOW()) and year(riqi)=year(now())");
		return $this->get_sum($query);
	}
	public function get_year_api($user,$type)
	{
		# code...
		$query = $this->db->query("select sum(number) from hhs_gongke where name='$user' and gongke_type='$type' and YEAR(riqi)=YEAR(NOW())");
		return $this->get_sum($query);
	}
	public function add($user,$type,$number,$beizhu)
	{
		//$query = $this->db->query("select * from hhs_gongke where name='$user' and gongke_type='$type' and TO_DAYS(riqi)=TO_DAYS(now())");
//		log_message('error','message'.$this->db->last_query());
		$data = array(
		'name' => $user,
		'gongke_type' => $type,
		'number' => $number,
		'beizhu' => $beizhu,
		'riqi' => date('Y-m-d H:i:s')
		);
		$this->db->insert('hhs_gongke', $data);
	}
	public function update($id,$number,$beizhu)
	{
		# code...
		$data = array(
			'number' => $number,
			'beizhu' => $beizhu
			);
		# code...
		$this->db->update('hhs_gongke', $data,array('id' => $id));
		log_message('error','message'.$this->db->last_query());
	}
	public function delete($id)
	{
		$this->db->query("DELETE FROM `hhs_gongke` WHERE `id` IN($id)");
		return true;
	}
	public function get_info_api($user,$type)
	{
		switch ($type) {
			case 'today':
				$query = $this->db->query("select id,gongke_type,number,riqi,beizhu from hhs_gongke where name='$user' and TO_DAYS(riqi)=TO_DAYS(now()) order by id desc");
				break;
			case 'month':
				$query = $this->db->query("select id,gongke_type,number,riqi,beizhu from hhs_gongke where name='$user' and MONTH(riqi)=MONTH(NOW()) and year(riqi)=year(now()) order by id desc");
				break;
			case 'year':
				$query = $this->db->query("select * from hhs_gongke where name='$user' and year(riqi)=year(now())");
				break;
			default:
				# code...
				break;
		}
		return $query->result_array();
	}
	public function get_info_api2($user,$start,$end)
	{
		$query = $this->db->query("select id,gongke_type,number,riqi,beizhu from hhs_gongke where name='$user' and riqi>'$start' and riqi < '$end' order by id desc");
	//	log_message('error','message'.$this->db->last_query());
		return $query->result_array();
	}
	public function get_year_info_api($user,$type,$start,$end)
	{
		$query = $this->db->query("select sum(number) from hhs_gongke where name='$user' and gongke_type='$type' and riqi>'$start' and riqi < '$end' order by id desc");
	//	log_message('error','message'.$this->db->last_query());
		return $this->get_sum($query);
	}

}

/* End of file fotang_model.php */
/* Location: ./application/models/fotang_model.php */