<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wuzhong extends CI_Controller {
	private $upload_format2="*.jpg; *.png; *.gif; *.PNG; *.JPG; *.GIF;";
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('wuzhong_model');
		//Do your magic here
		$this->sidebar = array("<li><a href=\"".base_url('fangsheng/index')."\">放生管理</a></li>",
					"<li><a href=\"".base_url('wuzhong/index')."\">放生物种</a></li>" );
	}
	public function index()
	{
		if ($this->input->post('mymethod') == 'delete') 
		{
			# code...
			$this->wuzhong_model->delete(rtrim($this->input->post('deleteid'), ','));
		}
		$total= $this->db->count_all('hhs_fangsheng');
		$page_config['perpage']=10;   //每页条数
		$page_config['part']=2;//当前页前后链接数量
		$page_config['url']='wuzhong/index';//url
		$page_config['seg']=3;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
		$page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
		$page_config['total']=$total;
		$this->load->library('mypage');
		$this->mypage->initialize($page_config);

		$offset = ($page_config['nowindex']-1)*($page_config['perpage']);
		$data['news'] = $this->wuzhong_model->get(($offset),$page_config['perpage']);
		$attributes = array('id' => 'indexform');
		$hidden = array('deleteid' => '','mymethod'=>'delete');
		$data['formurl'] = form_open('wuzhong/index', $attributes,$hidden);
		$data['arrayleft'] = $this->sidebar;
		$this->load->view('templates/head', $data);
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data);
		$this->load->view('wuzhong/index', $data);
		$this->load->view('templates/footer');
	}
	public function create()
	{
		$this->load->library('qbox');

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('text', 'text', 'required');
		$data['arrayleft'] = $this->sidebar;
		if ($this->form_validation->run() === FALSE)
		{
			$data['base'] = $this->config->item('base_url');
			$data['callback_path'] = base_url('uploadtest/callback');
			$data['upToken'] = $this->qbox->GetUploadURL();
			$data['upload_format2'] = $this->upload_format2;
			$this->load->view('templates/head', $data); 
			$this->load->view('templates/menu');
			$this->load->view('templates/left',$data); 
			$this->load->view('wuzhong/create',$data);
			$this->load->view('templates/footer');
		}
		else
		{
			$this->wuzhong_model->create();
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
	public function show()
	{
		$id=$this->uri->segment(3);
		$data=$this->wuzhong_model->getOne($id);
		$data['base'] = $this->config->item('base_url');
		$this->load->view('templates/head', $data);
		$this->load->view('wuzhong/show', $data); 
		$this->load->view('templates/footer'); 

	}
	public function edit()
	{
		$this->load->library('qbox');
		$id=$this->uri->segment(3);
		$data=$this->wuzhong_model->getOne($id);
		$data['base'] = $this->config->item('base_url');
		$data['callback_path'] = base_url('uploadtest/callback');
		$data['upToken'] = $this->qbox->GetUploadURL();
		$data['news_id'] = $id;
		$data['arrayleft'] = $this->sidebar;
		$data['upload_format2'] = $this->upload_format2;
		$this->load->view('templates/head', $data);  
		$this->load->view('templates/menu');
		$this->load->view('templates/left',$data); 
		$this->load->view('wuzhong/update',$data);
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
				$this->wuzhong_model->update($type_id);
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
	public function callback()
	{
		$this->load->library('qbox');
		$act = isset($_POST["action"]) ? strtolower(trim($_POST["action"])) : "";
		/**
		 * 响应并分发请求
		 */
		# 取得将要执行操作的类型
		log_message('error','callback is entry');
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

		    # 如果是未知操作，返回错误
		    default:
		        $resp = json_encode(array("code" => 400, "data" => array("errmsg" => "Invalid URL, Unknow <action>: $act")));
		        die($resp);
		}
		# code...
	}

}
/* End of file wuzhong.php */
/* Location: ./application/controllers/wuzhong.php */