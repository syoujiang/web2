<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class Fabao extends CI_Controller {

	private $sidebar;
	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model('fabao_model');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->sidebar = array("<li><a href=\"".base_url('fabao/lunbo')."\">轮播图</a></li>",
							"<li><a href=\"".base_url('fabao/fb_type')."\">法宝分类</a></li>",
							"<li><a href=\"".base_url('fabao/index')."\">法宝详情</a></li>",
							"<li><a href=\"".base_url('fabao/order')."\">法宝订单</a></li>",
							"<li><a href=\"".base_url('fabao/yunfei')."\">法宝运费须知</a></li>" );
		//$this->load->model('Model File');
	}
	public function index()
	{
		if ($this->input->post('mymethod') == 'delete') 
		{
			# code...
			$this->fabao_model->delete_fabao(rtrim($this->input->post('deleteid'), ','));
		}
		$page_config['seg']=3;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
		// echo $this->uri->total_segments();
		if($this->uri->total_segments() == 4)
		{
			if($this->uri->segment(3)=='on')
			{
				$id = $this->uri->segment(4);
				$this->fabao_model->Set_tuijian($id,1);
			}
			elseif ($this->uri->segment(3)=='off') 
			{
				$id = $this->uri->segment(4);
				$this->fabao_model->Set_tuijian($id,0);
			}
			$page_config['nowindex']=1;
		}
		else
		{
			$page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
		}
		$likename="";
		if ($this->input->post('mymethod') == 'search') 
		{
			# code...
			//$total= $this->fabao_model->search_count();
			//SELECT * FROM `magazine` WHERE CONCAT（`title`,`tag`,`description`） LIKE ‘%关键字%’
			$likename = $this->input->post('searchtext');
			$total= $this->fabao_model->get_fabao_numer($likename);
			$this->db->count_all('hhs_fabao');
		}
		else
		{
			$total= $this->db->count_all('hhs_fabao');
		}
		$page_config['perpage']=10;   //每页条数
		$page_config['part']=2;//当前页前后链接数量
		$page_config['url']='fabao/index';//url
		$page_config['seg']=3;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
		// $page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
		$page_config['total']=$total;
		$this->load->library('mypage');
		$this->mypage->initialize($page_config);

		$offset = ($page_config['nowindex']-1)*($page_config['perpage']);
		if($likename)
			$data['news'] = $this->fabao_model->get_fabao2(($offset),$page_config['perpage'],$likename);
		else
			$data['news'] = $this->fabao_model->get_fabao(($offset),$page_config['perpage']);
		$data['news_type']=$this->fabao_model->get_all_fabao_type();
		$attributes = array('id' => 'indexform');
		$hidden = array('deleteid' => '','mymethod'=>'');
		$data['formurl'] = form_open('fabao/index', $attributes,$hidden);
		$data['arrayleft'] = $this->sidebar;
		$data['searchtext'] = $this->input->post('searchtext');
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('fabao/index', $data);
		$this->load->view('templates/footer');
	}
	public function lunbo()
	{
		if ($this->input->post('mymethod') == 'delete') 
		{
			# code...
			$this->fabao_model->delete_lunbo(rtrim($this->input->post('deleteid'), ','));
		}
		if ($this->input->post('mymethod') == 'add') 
		{
			# code...
			$this->fabao_model->add_lunbo($this->input->post('lunbotu'));
		}
		$total= $this->db->count_all('hhs_lunbo');
		$page_config['perpage']=10;   //每页条数
		$page_config['part']=2;//当前页前后链接数量
		$page_config['url']='fabao/lunbo';//url
		$page_config['seg']=3;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
		$page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
		$page_config['total']=$total;
		$this->load->library('mypage');
		$this->mypage->initialize($page_config);

		$offset = ($page_config['nowindex']-1)*($page_config['perpage']);

		$fabao_id= $this->fabao_model->get_lunbo(($offset),$page_config['perpage']);
		$lunbo_data = array();
		$i=0;
		foreach ($fabao_id as $row)
		{
			$lunbo_data[$i]['fabao_id'] = $row['fabao_id'];
			$lunbo_data[$i]['fb_name']= $this->fabao_model->getFbName($row['fabao_id']);
			$lunbo_data[$i]['weight']=$row['weight'];
			$i=$i+1;
		}
		$data['news'] =$lunbo_data;
		$attributes = array('id' => 'indexform');
		$hidden = array('deleteid' => '','mymethod'=>'');
		$data['formurl'] = form_open('fabao/lunbo', $attributes,$hidden);
		$data['arrayleft'] = $this->sidebar;
		$data['fabao']=$this->fabao_model->get_fabao_no_lunbo();
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('fabao/lunbo', $data);
		$this->load->view('templates/footer');
	}
	public function yunfei()
	{
		$this->load->model('yunfei_model');
		if ($this->input->post('mymethod') == 'update') 
		{
			# code...
			$this->yunfei_model->update();
		}
		$data = $this->yunfei_model->get();
		// $attributes = array('id' => 'indexform');
		// $hidden = array('deleteid' => '','mymethod'=>'update');
		// $data['formurl'] = form_open('fabao/yunfei', $attributes,$hidden);
		 $data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('fabao/yunfei', $data);
		$this->load->view('templates/footer');
	}
	public function view($slug)
  	{
   		$data['news_item'] = $this->news_model->get_news($slug);
  	}	
	public function get_news($num,$offset)
	{
		$this->db->get('hhs_news',$num,$offset);
		return $query->result_array();
	}
	public function create()
	{
		$this->load->library('qbox');
		$data['title'] = '创建新的法宝';

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('text', 'text', 'required');
		$data['arrayleft'] = $this->sidebar;
		if ($this->form_validation->run() === FALSE)
		{
			$data['base'] = $this->config->item('base_url');
			$data['news_type'] = $this->fabao_model->get_all_fabao_type();
			$data['callback_path'] = base_url('uploadtest/callback');
			$data['upToken'] = $this->qbox->GetUploadURL();
			$this->load->view('templates/head', $data); 
			$this->load->view('templates/menu');
			$this->load->view('templates/left',$data); 
			$this->load->view('fabao/create',$data);
			$this->load->view('templates/footer');
		}
		else
		{
			$this->fabao_model->set_fabao();
			$data['base'] = $this->config->item('base_url');
			$data['showmsg']="添加成功";
			$data['indexurl']=site_url('news/index');
			$this->load->view('templates/head', $data); 
			$this->load->view('templates/menu');
			$this->load->view('templates/left'); 
			$this->load->view('templates/success',$data);
			$this->load->view('templates/footer');
		}
	}
	public function update()
	{
		$this->load->library('qbox');
		$id=$this->uri->segment(3);
		$data=$this->fabao_model->getOneFabao($id);
		$data['news_type']=$this->fabao_model->get_all_fabao_type();
		$data['base'] = $this->config->item('base_url');
		$data['callback_path'] = base_url('uploadtest/callback');
		$data['upToken'] = $this->qbox->GetUploadURL();
		$data['news_id'] = $id;
		$data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head', $data);  
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data); 
		$this->load->view('fabao/update',$data);
		$this->load->view('templates/footer');
	}
	public function commit()
	{
		 $type_id = $_POST['news_id'] + 0;
        if( $type_id <= 0 )
        {
			show_error('Error');
        }
		$data['arrayleft'] = $this->sidebar;
		switch ($this->uri->segment(3)) {
			case 'update':
				# code...
				$this->fabao_model->update_fabao($type_id);
				$data['showmsg']="更新成功";
				$data['indexurl']=site_url('fangsheng/index');
				$this->load->view('templates/head');  
				$this->load->view('templates/menu');
				$this->load->view('templates/left',$data);
				$this->load->view('templates/success',$data);
				$this->load->view('templates/footer');
				break;
			case 'insert':
				$this->create();
				break;
			default:
				# code...
				break;
		}
	}
	public function show()
	{
		$id=$this->uri->segment(3);
		$this->load->model('fabao_model');
		$data=$this->fabao_model->getOneFabao($id);
		$data['base'] = $this->config->item('base_url');
		$this->load->view('templates/head', $data);
		$this->load->view('fabao/show', $data); 
		$this->load->view('templates/footer'); 

	}
	public function fb_type($value='')
	{
		# code...
		$total= $this->db->count_all('hhs_fabao_type');
		$page_config['perpage']=10;   //每页条数
		$page_config['part']=2;//当前页前后链接数量
		$page_config['url']='fabao_type/index';//url
		$page_config['seg']=3;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
		$page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
		$page_config['total']=$total;
		$this->load->library('mypage');
		$this->mypage->initialize($page_config);
		$offset = ($page_config['nowindex']-1)*($page_config['perpage']);

		$data['news'] = $this->fabao_model->get_fabao_type(($offset),$page_config['perpage']);
		$attributes = array('id' => 'indexform');
		$hidden = array('deleteid' => '','mymethod'=>'delete');
		$data['formurl'] = form_open('news/index', $attributes,$hidden);
		$data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('fabao_type/index', $data);
		$this->load->view('templates/footer');
	}
	public function zhuyin($value='')
	{
		# code...
	}
	public function create_type()
	{
		# code...
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$data['title'] = '创建新的法宝类别';
		$this->form_validation->set_rules('title', '类别','required|max_length[16]|is_unique[hhs_news_type.news_type]');
		$this->form_validation->set_message('required', '%s不能为空');
		$this->form_validation->set_message('max_length', '%s最大长度不能超过16');
		$this->form_validation->set_message('is_unique', '%s已经存在');
		$data['arrayleft'] = $this->sidebar;
		if ($this->form_validation->run() === FALSE)
		{
			$data['base'] = $this->config->item('base_url');
			$this->load->view('templates/head', $data); 
			$this->load->view('templates/menu');
			$this->load->view('templates/left',$data); 
			$this->load->view('fabao_type/create');
			$this->load->view('templates/footer');
		}
		else
		{
			$this->fabao_model->set_fabao_type();
			$data['base'] = $this->config->item('base_url');
			$this->load->view('templates/head', $data); 
			$data['showmsg']="添加成功";
			$data['indexurl']=site_url('fabao_type/index');
			$this->load->view('templates/head');  
			$this->load->view('templates/menu');
			$this->load->view('templates/left',$data);
			$this->load->view('templates/success',$data);
			$this->load->view('templates/footer');
		}
	}
	public function edit_type()
	{
		# code...
		$id=$this->uri->segment(3);
		$this->load->model('news_model');
		$data=$this->fabao_model->getOneFabaoType($id);
		$data['base'] = $this->config->item('base_url');
		$data['news_type_id'] = $id;
		$data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head', $data); 
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data); 
		$this->load->view('fabao_type/update',$data);
		$this->load->view('templates/footer');
	}
	public function order()
	{
		if ($this->input->post('mymethod') == 'delete') 
		{
			# code...
			$this->fabao_model->delete_order(rtrim($this->input->post('deleteid'), ','));
		}


		$total= $this->db->count_all('hhs_order');
		$page_config['perpage']=10;   //每页条数
		$page_config['part']=2;//当前页前后链接数量
		$page_config['url']='fabao/order';//url
		$page_config['seg']=3;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
		$page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
		$page_config['total']=$total;
		$this->load->library('mypage');
		$this->mypage->initialize($page_config);

		$offset = ($page_config['nowindex']-1)*($page_config['perpage']);
		$data['news'] = $this->fabao_model->get_order(($offset),$page_config['perpage']);
		$attributes = array('id' => 'indexform');
		$hidden = array('deleteid' => '','mymethod'=>'delete');
		$data['formurl'] = form_open('fabao/order', $attributes,$hidden);
		$data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('fabao/order', $data);
		$this->load->view('templates/footer');
	}

	public function order_update()
	{
		$id=$this->uri->segment(3);
		$data = $this->fabao_model->get_order_by_id($id);
		$content=$this->fabao_model->get_one_order($data['order_id']);
        $content_count = count($content);
        for ($i=0; $i <$content_count ; $i++) { 
            $content[$i]['fabao_id']=$this->fabao_model->getFbName($content[$i]['fabao_id']);
        }
        $data['fabao_info']=$content;
		$data['formurl'] = form_open('fabao/update_order');
		$data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('fabao/order_update', $data);
		$this->load->view('templates/footer');
	}
	public function update_order()
	{
		$order_id=$this->input->post('order_id');
		$this->fabao_model->update_order($order_id);
		$data['base'] = $this->config->item('base_url');
		
		$data['showmsg']="更新成功";
		$data['indexurl']=site_url('fabao/index');
		$data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head');  
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('templates/success',$data);
		$this->load->view('templates/footer');
	}
	function commit_type()
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
				$data['arrayleft'] = $this->sidebar;
				if ($this->form_validation->run() === FALSE)
				{
					$data=$this->fabao_model->getOneNewsType($type_id);
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
					$this->fabao_model->update_fabao_type($type_id);
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
	public function callback()
	{
		$this->load->library('qbox');
		$act = isset($_POST["action"]) ? strtolower(trim($_POST["action"])) : "";
		/**
		 * 响应并分发请求
		 */
		# 取得将要执行操作的类型
		log_message('error',"callback".$act);
		switch ($act) {

		    # 如果是写表操作
		    case "insert":
		    
		        # 首先接值
		        $filekey = isset($_POST["file_key"]) ? trim($_POST["file_key"]) : "";
		        $filename = isset($_POST["file_name"]) ? trim($_POST["file_name"]) : "";
		        $filesize = isset($_POST["file_size"]) ? (int)trim($_POST["file_size"]) : 0;
		        $filetype = isset($_POST["file_type"]) ? trim($_POST["file_type"]) : "";

		        # 然后检查有效性
		        if($filekey == "" || $filename == ""){
		            $resp = json_encode(array("code" => 400, "data" => array("errmsg" => "Invalid Params, <file_key> and <file_name> cannot be empty")));
		            die($resp);
		        }
		        $previewURL = $this->qbox->GetPictureURL($filekey,$filename);
		        # 再写表
		        // $timenow = time();
		        // $insertSQL = "INSERT INTO uploads(user_id, file_key, file_name, file_size, file_type, created_at)
		        //                 VALUES('admin', '$filekey', '$filename', '$filesize', '$filetype', '$timenow')";	        
				//	$this->db->query($insertSQL);
				//  $lastInsertId = $this->db->insert_id();
				

		        # 最后返回处理结果
		        if (isset($previewURL)) {
		            die(json_encode(array(	"code" => 200,
		            						"sqlid"=>$previewURL, 
		            						"fkey"=>$filekey, 
		            						"fname"=>$filename, 
		            						"data" => array("success" => true))));
		        } else {
		            die(json_encode(array("code" => 499, "data" => array("errmsg" => "Insert Failed"))));
		        }
		        break;
		    case 'delete':
		    	# code...
				$filekey = isset($_POST["file_key"]) ? trim($_POST["file_key"]) : "";
				$this->qbox->Delete($filekey);
	            die(json_encode(array(	"code" => 200,
						"data" => array("success" => true))));
		    	break;
		    # 如果是未知操作，返回错误
		    default:
		        $resp = json_encode(array("code" => 400, "data" => array("errmsg" => "Invalid URL, Unknow <action>: $act")));
		        die($resp);
		}
		# code...
	}

}

/* End of file fabao.php */
/* Location: ./application/controllers/fabao.php */