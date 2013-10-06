<?php
/**
* 
*/
class news_type extends CI_Controller
{
	
	function __construct()
	{
		# code...
		parent::__construct();
		$this->load->helper('form');

		$this->load->model('news_model');
		$this->load->database();
		$this->load->helper('url');
	}
	function index()
	{

		if ($this->input->post('mymethod') == 'delete') 
		{
			# code...
			$this->news_model->delete_news_types(rtrim($this->input->post('deleteid'), ','));
		}
		$page_config['seg']=3;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
		if($this->uri->total_segments() == 5)
		{
			if($this->uri->segment(3)=='up')
			{
				$wid = $this->uri->segment(4);
				$id = $this->uri->segment(5);
				$query = $this->db->query("select * from `hhs_news_type` where `weight` < '$wid' order by `weight` desc limit 1");
				if ($query->num_rows() > 0)
				{	
					$rows = $query->row();
					$this->db->query("update `hhs_news_type` set `weight`='$rows->weight' where `weight`='$wid'"); 
					$this->db->query("update `hhs_news_type` set `weight`='$wid' where id='$rows->id'"); 
				}
			}
			elseif ($this->uri->segment(3)=='down') 
			{
				$wid = $this->uri->segment(4);
				$id = $this->uri->segment(5);
				$query = $this->db->query("select * from `hhs_news_type` where `weight` > '$wid' order by `weight` asc limit 1");
				if ($query->num_rows() > 0)
				{	
					$rows = $query->row();
					$this->db->query("update `hhs_news_type` set `weight`='$rows->weight' where `weight`='$wid'"); 
					$this->db->query("update `hhs_news_type` set `weight`='$wid' where id='$rows->id'"); 
				}
			}
			$page_config['nowindex']=1;
		}
		else
		{
			$page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
		}
		
		$total= $this->db->count_all('hhs_news_type');
		$page_config['perpage']=10;   //每页条数
		$page_config['part']=2;//当前页前后链接数量
		$page_config['url']='news_type/index';//url


		$page_config['total']=$total;
		$this->load->library('mypage');
		$this->mypage->initialize($page_config);
		$offset = ($page_config['nowindex']-1)*($page_config['perpage']);
		$data['news'] = $this->news_model->get_news_type($offset,$page_config['perpage']);

		$data['title'] = 'News archive';
		$attributes = array('id' => 'indexform');
		$hidden = array('deleteid' => '','mymethod'=>'delete');
		$data['formurl'] = form_open('news_type/index', $attributes,$hidden);
		$data['base'] = $this->config->item('base_url');
		$data['arrayleft'] = array("<li><a href=\"".base_url('news_type')."\">资讯类别</a></li>",
									"<li><a href=\"".base_url('news')."\">资讯管理</a></li>" );
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('news_type/index', $data);
		$this->load->view('templates/footer');
	}
	public function view($slug)
  	{
   		$data['news_item'] = $this->news_model->get_news($slug);
  	}	
	public function get_news($slug = FALSE)
	{
		if ($slug === FALSE)
		{
			$query = $this->db->get('hhs_news');
			return $query->result_array();
		}

		$query = $this->db->get_where('hhs_news', array('slug' => $slug));
		return $query->row_array();
	}
	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$data['title'] = '创建新的资讯类别';
		$this->form_validation->set_rules('title', '类别','required|max_length[16]|is_unique[hhs_news_type.news_type]');
		$this->form_validation->set_message('required', '%s不能为空');
		$this->form_validation->set_message('max_length', '%s最大长度不能超过16');
		$this->form_validation->set_message('is_unique', '%s已经存在');
		$data['arrayleft'] = array("<li><a href=\"".base_url('news_type')."\">资讯类别</a></li>",
							"<li><a href=\"".base_url('news')."\">资讯管理</a></li>" );
		if ($this->form_validation->run() === FALSE)
		{
			$data['base'] = $this->config->item('base_url');
			$this->load->view('templates/head', $data); 
			$this->load->view('templates/menu');
			$this->load->view('templates/left',$data); 
			$this->load->view('news_type/create');
			$this->load->view('templates/footer');
		}
		else
		{
			$this->news_model->set_news_type();
			$data['base'] = $this->config->item('base_url');
			$this->load->view('templates/head', $data); 
			$data['showmsg']="添加成功";
			$data['indexurl']=site_url('news_type/index');
			$this->load->view('templates/head');  
			$this->load->view('templates/menu');
			$this->load->view('templates/left',$data);
			$this->load->view('templates/success',$data);
			$this->load->view('templates/footer');
		}
	}
	function edit()
	{
		$id=$this->uri->segment(3);
		$this->load->model('news_model');
		$data=$this->news_model->getOneNewsType($id);
		$data['base'] = $this->config->item('base_url');
		$data['news_type_id'] = $id;
		$data['arrayleft'] = array("<li><a href=\"".base_url('news_type')."\">资讯类别</a></li>",
							"<li><a href=\"".base_url('news')."\">资讯管理</a></li>" );
		$this->load->view('templates/head', $data); 
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data); 
		$this->load->view('news_type/update',$data);
		$this->load->view('templates/footer');
	}
	function commit()
	{
		switch ($this->uri->segment(3)) {
			case 'update':
				$type_id = $_POST['news_type_id'] + 0;
				if( $type_id <= 0 )
				{
					show_error('Error');
				}
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', '类别','required|max_length[16]|is_unique[hhs_news_type.news_type]');
				$this->form_validation->set_message('required', '%s不能为空');
				$this->form_validation->set_message('max_length', '%s最大长度不能超过16');
				$this->form_validation->set_message('is_unique', '%s已经存在');
						$data['arrayleft'] = array("<li><a href=\"".base_url('news_type')."\">资讯类别</a></li>",
									"<li><a href=\"".base_url('news')."\">资讯管理</a></li>" );
				if ($this->form_validation->run() === FALSE)
				{
					$data=$this->news_model->getOneNewsType($type_id);
					$data['base'] = $this->config->item('base_url');
					$data['news_type_id'] = $type_id;
					$this->load->view('templates/head', $data); 
					$this->load->view('templates/menu');
					$this->load->view('templates/left',$data); 
					$this->load->view('news_type/update',$data);
					$this->load->view('templates/footer');
				}
				else
				{
					$this->news_model->update_news_type($type_id);
					$data['base'] = $this->config->item('base_url');
					$this->load->view('templates/head', $data); 
					$data['showmsg']="更新成功";
					$data['indexurl']=site_url('news_type/index');
					$this->load->view('templates/head');  
					$this->load->view('templates/menu');
					$this->load->view('templates/left',$data);
					$this->load->view('templates/success',$data);
					$this->load->view('templates/footer');
				}
				break;
			case 'create':
				$this->create();
				break;
			default:
				# code...
				break;
		}
	}
}
?>