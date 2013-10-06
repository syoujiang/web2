<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	function __construct()
	{
		$this->load->database();
		$this->load->helper('date');
	}
	function generate_token ($len = 32)
	{

		// Array of potential characters, shuffled.
		$chars = array(
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
		'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
		'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
		'0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
		);
		shuffle($chars);

		$num_chars = count($chars) - 1;
		$token = '';

		// Create random token at the specified length.
		for ($i = 0; $i < $len; $i++)
		{
			$token .= $chars[mt_rand(0, $num_chars)];
		}

		return $token;
	}
 	public function login($username, $password)
 	{
		$this->db->select('id, username, password');
		$this->db->from('hhs_users');
		$this->db->where('username', $username);
		$this->db->where('password', MD5($password));
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
 	}
 	public function getUserName($token)
 	{
 		log_message('debug','message'.$token);
 		return "admin";
 	}
 	public function register($username,$mail,$password,&$msg,&$token)
 	{
 		// check username
		$this->db->where('username', $username);
		$this->db->from('hhs_users');
		if($this->db->count_all_results()>0)
		{
			$msg="昵称".$username."已经被注册了";
			return 0;
		}
 		// check mail
		$this->db->where('mail', $mail);
		$this->db->from('hhs_users');
		if($this->db->count_all_results()>0)
		{
			$msg="邮箱".$mail."已经被注册了";
			return 0;
		}
		$token=$this->generate_token(32);
		$data = array(
               'username' => $username ,
               'mail' => $mail ,
               'password' => md5($password)
            );
		$this->db->insert('hhs_users', $data); 
		$data = array(
				'mail' => $mail,
               'token' => $token
            );
		$this->db->insert('hhs_users_token', $data);
 		return 1;
 	}
 	public function check_login($mail,$password,&$token)
 	{
 		// check username
		$this->db->where('mail', $mail);
		$this->db->where('password', md5($password));
		$this->db->from('hhs_users');
		if($this->db->count_all_results()>0)
		{
			if($token!="")
			{	
				log_message('debug','token '.$token);
				$this->db->where('token', $token);
				$this->db->delete('hhs_users_token');
			}
			$token=$this->generate_token(32);
			$data = array(
					'mail' => $mail,
	               'token' => $token
	            );
			$this->db->insert('hhs_users_token', $data);
	 		return 1;
		}
		else
		{
			return 0;
		}
 	}
 	public function logout($mail,$token)
 	{
		$data = array('token' => $token,
	        		   'mail' => $mail);
		$this->db->where($data);
		$this->db->delete('hhs_users_token');
		return 1;
 	}
 	public function reset_passwd($mail,&$msg)
 	{
		$this->db->where('mail', $mail);
		$this->db->from('hhs_users');
		if($this->db->count_all_results()==0)
		{
			$msg="邮箱".$mail."没有注册";
			return 0;
		}
		$this->db->where('mail', $mail);
		$this->db->from('hhs_reset_pwd');
		if($this->db->count_all_results()>0)
		{
			$msg="请求已经提交，正在处理中。。请不要重复提交。";
			return 0;
		}
		// inert reset table
		$data = array(
			'mail' => $mail ,
			'result' => '1'
			);
		$this->db->insert('hhs_reset_pwd', $data); 
		$msg="已经交由管理员处理，请稍候！";
		return 1;
 	}
 	public function getMail($token)
 	{
 		$query = $this->db->get_where('hhs_users_token', array('token' => $token));
		foreach ($query->result_array() as $row)
		{
   			return $row['mail'];
   		}
   		return null;
 	}
 	public function create_alipay($mail,$price,$app,&$order_sn)
 	{
 		$order_sn = date('ymdHis').substr(microtime(),2,4);
 		log_message('debug','order sn '.$order_sn);
 		$data = array(
               'order_number' => $order_sn ,
               'mail' => $mail ,
               'price' => $price,
               'price_app' => $app,
               'order_status' => "1"
            );
		$this->db->insert('hhs_alipay_order', $data); 
 	}
 	public function get_alipay($order_sn)
 	{
 		$this->db->select('order_number,price, price_app');
		$query = $this->db->get_where('hhs_alipay_order', array('order_number' =>$order_sn,
														'order_status' => '1'));
		return $query;
 	}
 	public function get_all_alipay($mail)
 	{
 		$this->db->select('price_time,price_app, price');
 		$query = $this->db->get_where('hhs_alipay_order', array('mail' =>$mail,
														'order_status' => '2'));
		return $query->result_array();
 	}
 	public function set_address($address,$mail)
 	{
 		$data = array('address' => $address);
 		$this->db->where('mail',$mail);
		$this->db->update('hhs_users', $data);
 	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
