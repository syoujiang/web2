<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/Alipay_Controller.php';

class Alipay extends Alipay_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('user_model');
	}

	public function index()
	{
		$id=$this->uri->segment(3);
		log_message('debug','message'.$id);
		if($id != "")
		{
			log_message('debug','message11111111111');
			$date=$this->user_model->get_alipay($id);
			if($date->num_rows()>0)
			{
				$this->load->view('alipay/index',$date->row_array());
			}
			else
			{
				$this->load->view('alipay/error');
			}
			
		}
		else
		{
			$this->load->view('alipay/error');
		}	
		
	}
	public function create()
	{
		log_message('debug','Alipay post');
		echo $this->notify_verify();
	}
	public function notify_url()
	{
		log_message('debug','notify_url ');
		$this->_notify_url();
	}
	public function call_back_url()
	{
		log_message('debug','call_back_url ');
		// $this->_call_back_url();
					//商户订单号
		$out_trade_no = $_GET['out_trade_no'];

		//支付宝交易号
		$trade_no = $_GET['trade_no'];

		//交易状态
		$result = $_GET['result'];
		log_message('error','call_back_url result '.$result);
		log_message('error','call_back_url out_trade_no '.$out_trade_no);
		if($result=='success')
		{
			$data = array('order_status ' => '2');
			$this->db->where('order_number',$out_trade_no);
			$this->db->update('hhs_alipay_order', $data);
			echo "支付成功1";
		}
		else
		{
			echo "支付失败2";
		}

	}
}

/* End of file alipay.php */
/* Location: ./application/controllers/alipay.php */