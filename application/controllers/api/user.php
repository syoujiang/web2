<?php defined('BASEPATH') OR exit('No direct script access allowed');

// namespace D;

/**
 * fabao
 *
 * This is an fabao of a few basic content interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
class User extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('fabao_model');
		$this->load->model('news_model');
                $this->load->model('Fangsheng_model');
	}
	public function register_post()
	{
		log_message('debug','reigster_post');
		$username=$this->post("username");
		$mail=$this->post("mail");
		$password=$this->post("password");
		$result=$this->user_model->register($username,$mail,$password,$msg,$token);
		if($result==true)
		{
	        $sendmsg = array('result' => "1",
	                        'reason' => "注册成功",
	                        'token' => $token);
	        $this->response($sendmsg, 200); // 200 being the HTTP response code
		}
		else
		{
	        $sendmsg = array('result' => "0",
	                        'reason' => $msg);
			$this->response($sendmsg, 200); // 200 being the HTTP response code
		}

	}

	public function login_post()
	{
		log_message('debug','login_post');
		
		$mail=$this->post("mail");
		$password=$this->post("password");
		$token=$this->post("token");
		$result=$this->user_model->check_login($mail,$password,$token);
		if($result==true)
		{
	        $sendmsg = array('result' => "1",
	                        'reason' => "登入成功",
	                        'token' => $token);
	        $this->response($sendmsg, 200); // 200 being the HTTP response code
		}
		else
		{
	        $sendmsg = array('result' => "0",
	                        'reason' => "用户名密码错误");
			$this->response($sendmsg, 200); // 200 being the HTTP response code
		}
	}
	public function login_delete()
	{
		log_message('debug','login_delete');
		$mail=$this->delete("mail");
		$token=$this->delete("token");
		$this->user_model->logout($mail,$token);
        $sendmsg = array('result' => "1",
                        'reason' => "注销成功");
		$this->response($sendmsg, 200); // 200 being the HTTP response code

	}
	public function reset_put()
	{
		log_message('debug','login_delete');
		$mail=$this->put("mail");
		$result=$this->user_model->reset_passwd($mail,$msg);
		if($result==true)
		{
	        $sendmsg = array('result' => "1",
	                        'reason' => $msg);
	        $this->response($sendmsg, 200); // 200 being the HTTP response code
		}
		else
		{
	        $sendmsg = array('result' => "0",
	                        'reason' =>  $msg);
			$this->response($sendmsg, 200); // 200 being the HTTP response code
		}
	}
	// 我的结缘
	public function order_get()
	{
		$token=$this->get("token");
		if($token=="")
		{
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
		else
		{
			log_message('debug','token '.$token);
			log_message('debug','start check token is valid');

			$mail=$this->user_model->getMail($token);
			if($mail == null)
			{
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
			else
			{
				log_message('debug','mail '.$mail);
				$sendmsg=$this->fabao_model->get_order_by_mail_api($mail);
				$this->response($sendmsg, 200); // 200 being the HTTP response code
			}
		}

	}
	// 我的收藏
	public function news_get()
	{
                $this->load->library('qbox');
		$token=$this->get("token");
		if($token=="")
		{
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
		else
		{
			log_message('debug','token '.$token);
			log_message('debug','start check token is valid');
			$mail=$this->user_model->getMail($token);
			if($mail == null)
			{
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
			else
			{
				log_message('debug','mail '.$mail);
                                //get news collect
				$content=$this->news_model->get_news_by_mail_api($mail);
                                $sendmsg = array();
                                $i=0;
                                foreach ($content as $rows)  
                                {  
                                    $rows['news_summary_fkey']=$this->qbox->GetDownloadURL($rows['news_summary_fkey']);
                                    $sendmsg[$i]=$rows;
                                    $i++;
                                }
                                //get kaishi collect
                                $content2=$this->news_model->get_fangsheng_by_mail_api($mail);
                                $sendmsg2 = array();
                                $i=0;
                                foreach ($content2 as $rows)  
                                {  
                                    $rows['kaishi_summary_fkey']=$this->qbox->GetDownloadURL($rows['kaishi_summary_fkey']);
                                    $sendmsg2[$i]=$rows;
                                    $i++;
                                }
                                $message = array('news' => $sendmsg, 'kaishi' => $sendmsg2);
				$this->response($message, 200); // 200 being the HTTP response code
			}
		}
	}
	// 添加收藏
	public function news_post()
	{
		$token=$this->post("token");
		if($token=="")
		{
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
		else
		{
			log_message('debug','token '.$token);
			log_message('debug','start check token is valid');
			$mail=$this->user_model->getMail($token);
			if($mail == null)
			{
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
			else
			{
				$id=$this->post("id");
				$sendmsg=$this->news_model->add_news_to_collect($mail,$id,0);
				$message = array('result' => '1',
					'reason' => "添加成功");
					$this->response($message, 200); // 200 being the HTTP response code
			}
		}
	}
	// 删除收藏
	public function news_delete()
	{
		$token=$this->delete("token");
		if($token=="")
		{
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
		else
		{
			log_message('debug','token '.$token);
			log_message('debug','start check token is valid');
			$mail=$this->user_model->getMail($token);
			if($mail == null)
			{
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
			else
			{
				$id=$this->delete("id");
				$sendmsg=$this->news_model->delete_news_from_collect($mail,$id,0);
				$message = array('result' => '1',
					'reason' => "删除成功");
					$this->response($message, 200); // 200 being the HTTP response code
			}
		}
	}
        	// 添加放生开示收藏
	public function kaishi_post()
	{
		$token=$this->post("token");
		if($token=="")
		{
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
		else
		{
			log_message('debug','token '.$token);
			log_message('debug','start check token is valid');
			$mail=$this->user_model->getMail($token);
			if($mail == null)
			{
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
			else
			{
				$id=$this->post("id");
				$sendmsg=$this->fangsheng_model->add_fangsheng_to_collect($mail,$id);
				$message = array('result' => '1',
					'reason' => "添加成功");
					$this->response($message, 200); // 200 being the HTTP response code
			}
		}
	}
	// 删除放生开示收藏
	public function kaishi_delete()
	{
		$token=$this->delete("token");
		if($token=="")
		{
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
		else
		{
			log_message('debug','token '.$token);
			log_message('debug','start check token is valid');
			$mail=$this->user_model->getMail($token);
			if($mail == null)
			{
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
			else
			{
				$id=$this->delete("id");
				$sendmsg=$this->fangsheng_model->delete_fangsheng_from_collect($mail,$id);
				$message = array('result' => '1',
					'reason' => "删除成功");
					$this->response($message, 200); // 200 being the HTTP response code
			}
		}
	}
	public function address_post()
	{
		$token=$this->post("token");
		if($token=="")
		{
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
		else
		{
			log_message('debug','token '.$token);
			log_message('debug','start check token is valid');
			$mail=$this->user_model->getMail($token);
			if($mail == null)
			{
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
			else
			{
				$address=$this->post("address");
				$this->user_model->set_address($address,$mail);
				$message = array('result' => '1',
					'reason' => "地址更新成功");
				$this->response($message, 200); // 200 being the HTTP response code
			}
		}
	}


	//get alipay order
	public function alipay_get()
	{
		$token=$this->get("token");
		if($token=="")
		{
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
		else
		{
			log_message('debug','token '.$token);
			log_message('debug','start check token is valid');
			$mail=$this->user_model->getMail($token);
			if($mail == null)
			{
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
			else
			{
				$sendmsg=$this->user_model->get_all_alipay($mail);
				$this->response($sendmsg, 200); // 200 being the HTTP response code
			}
		}
	}

	//create alipay order
	public function alipay_post()
	{
		$token=$this->post("token");
		if($token=="")
		{
                    $mail="hhs_unknow";
		}
                else
                {
                    $mail=$this->user_model->getMail($token);
                    if($mail == null)
                    {
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
                        return;
                    }
                }
                log_message('debug','token '.$mail);


                $price=0;
                $type=$this->post("type");
                $price=$this->post("custom_price");
                $client_sn=$this->post("client_sn");
                switch ($type) {
                        case '0':
                                $app="经书助印";
                                break;
                        case '1':
                                $app="放生";
                                break;				
                        default:
                                break;
                }
                if($price>0)
                {	
                        $sendmsg=$this->user_model->create_alipay_client($mail,$price,$app,$client_sn);
                        $message = array('order_sn' => $client_sn,
                                                                'result' => '1',
                                                                'reason' => "订单生产成功");
                        $this->response($message, 200); // 200 being the HTTP response code
                }
                else
                {
                        $message = array('result' => '0',
                                                        'reason' => "捐助的数量要>0");
                                                        $this->response($message, 200); // 200 being the HTTP response code
                }


	}
}

/* End of file user.php */
/* Location: ./application/controllers/api/user.php */