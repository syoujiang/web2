<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Yijian extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('yijian_model');
		//Do your magic here
		$this->sidebar = array("<li><a href=\"".base_url('yigong/index')."\">义工管理</a></li>",
								"<li><a href=\"".base_url('yijian/index')."\">意见反馈</a></li>");
	}

	public function index()
	{
		if ($this->input->post('mymethod') == 'delete') 
		{
			# code...
			$this->yijian_model->delete(rtrim($this->input->post('deleteid'), ','));
		}
		$total= $this->db->count_all('hhs_yijian');
		$page_config['perpage']=10;   //每页条数
		$page_config['part']=2;//当前页前后链接数量
		$page_config['url']='yijian/index';//url
		$page_config['seg']=3;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
		$page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
		$page_config['total']=$total;
		$this->load->library('mypage');
		$this->mypage->initialize($page_config);

		$offset = ($page_config['nowindex']-1)*($page_config['perpage']);
		$data['news'] = $this->yijian_model->get(($offset),$page_config['perpage']);
		$attributes = array('id' => 'indexform');
		$hidden = array('deleteid' => '','mymethod'=>'delete');
		$data['formurl'] = form_open('yijian/index', $attributes,$hidden);
		$data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('yijian/index', $data);
		$this->load->view('templates/footer');
	}

}

/* End of file other.php */
/* Location: ./application/controllers/other.php */