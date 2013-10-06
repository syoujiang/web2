<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Fotang extends REST_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('fotang_model');
		$this->load->model('user_model');
	}
	public function today_get()
	{
		$username = $this->input->server('PHP_AUTH_USER');
		if($username==null)
		{
			log_message('debug','have no username ');
			$token=$this->get("token");
			$username=$this->user_model->getUserName($token);
			log_message('debug','token is '.$token);
		}
		if($username==null)
		{
			$this->_force_login();
		}
		log_message('debug','have username ('.$username.')');
        # code...
        // $message = $this->fotang_model->get_today_api($username);
        # (念佛，诵经，持咒，吃素)
        $type = array('念佛','诵经','持咒','吃素');
		for ($i=0; $i <4 ; $i++) { 
			$message[$i]['gongke_type']=$type[$i];
			$message[$i]['numer']=$this->fotang_model->get_today_api($username,$type[$i]);
		}
        $this->response($message, 200); // 200 being the HTTP response code
	}
	public function month_get()
	{
		$username = $this->input->server('PHP_AUTH_USER');
        # (念佛，诵经，持咒，吃素)
        $type = array('念佛','诵经','持咒','吃素');
		for ($i=0; $i <4 ; $i++) { 
			$message[$i]['gongke_type']=$type[$i];
			$message[$i]['numer']=$this->fotang_model->get_month_api($username,$type[$i]);
		}
        $this->response($message, 200); // 200 being the HTTP response code
	}
	public function year_get()
	{
		$username = $this->input->server('PHP_AUTH_USER');
        # (念佛，诵经，持咒，吃素)
        $type = array('念佛','诵经','持咒','吃素');
		for ($i=0; $i <4 ; $i++) { 
			$message[$i]['gongke_type']=$type[$i];
			$message[$i]['numer']=$this->fotang_model->get_year_api($username,$type[$i]);
		}
        $this->response($message, 200); // 200 being the HTTP response code
	}
	public function gongke_post()
	{
		$username = $this->input->server('PHP_AUTH_USER');
        # code...
        $type=$this->post('type');    
        $number=$this->post('number');
        $beizhu=$this->post('beizhu');
        if(!$type)
        {
        	$message = array('result' => '0',
			'reason' => "添加失败");
			$this->response($message, 200); // 200 being the HTTP response code
        }
        else
        {
        	$this->fotang_model->add($username,$type,$number,$beizhu);
			$message = array('result' => '1',
			'reason' => "添加成功");
			$this->response($message, 200); // 200 being the HTTP response code
		}
	}
	public function gongke_put()
	{
        # code...
        $id=$this->put('id');  
        log_message('error','message'.$id);  
        $number=$this->put('number');
        $beizhu=$this->put('beizhu');
        $this->fotang_model->update($id,$number,$beizhu);
		$message = array('result' => '1',
			'reason' => "更新成功");
			$this->response($message, 200); // 200 being the HTTP response code
		# code...
	}
	public function gongke_delete()
	{
        # code...
        $id=$this->delete('id');  
        log_message('error','message'.$id);  
        $this->fotang_model->delete($id);
		$message = array('result' => '1',
			'reason' => "删除成功");
			$this->response($message, 200); // 200 being the HTTP response code
		# code...
	}


	public function today_info_get()
	{
		$username = $this->input->server('PHP_AUTH_USER');
		$message = $this->fotang_model->get_info_api($username,'today');
		$this->response($message, 200); // 200 being the HTTP response code
	}
	public function month_info_get()
	{
		$username = $this->input->server('PHP_AUTH_USER');
		$month=$this->get('id');
		if($month){
			$starttime=$month."-1 00:00:00";
			$endtime=$month."-31 23:23:59";
			$message = $this->fotang_model->get_info_api2($username,$starttime,$endtime);
		}
		else{
			$message = $this->fotang_model->get_info_api($username,'month');
		}
		$this->response($message, 200); // 200 being the HTTP response code
	}
	public function year_info_get()
	{
		$username = $this->input->server('PHP_AUTH_USER');
		$count = date("n");
		$type = array('念佛','诵经','持咒','吃素');

		for ($i=$count; $i>0 ; $i--) {   
			$starttime=date("Y-").$i."-1 00:00:00";
			$endtime=date("Y-").$i."-31 23:23:59";
			$month=date("Y-").$i;

			for ($j=0; $j <4 ; $j++) { 
				$message[$month][$j]['gongke_type']=$type[$j];
				$message[$month][$j]['numer']=$this->fotang_model->get_year_info_api($username,$type[$j],$starttime,$endtime);
			}
		}
		$this->response($message, 200); // 200 being the HTTP response code
	}
}

/* End of file fotang.php */
/* Location: ./application/controllers/api/fotang.php */