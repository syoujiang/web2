<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploadtest extends CI_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->helper(array('url', 'form'));
	   $this->load->library(array('session', 'encrypt'));
	   $this->load->helper('url');
	   $this->load->model('news_model');
	   $this->load->model('huodong_model');
		$this->load->library('qbox');
	}

	public function index()
	{
		$token['uptoken'] = $this->qbox->GetUploadURL();
		$token['fileprev']="11";
		$this->load->view('upload_form',$token);
	}
	function urlsafe_base64_decode($string) {

		$data = str_replace(array('-','_'), array('+','/'), $string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}  // urlsafe_base64_decode
	public function result()
	{
		log_message('error','message22222222222222');
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		echo $_GET['upload_ret'];
		$qniu_res=$this->urlsafe_base64_decode($_GET['upload_ret']);
		$obj=json_decode($qniu_res);
		$token['uptoken'] = $this->qbox->GetUploadURL();
		$token['filename'] =date('ymdHis').substr(microtime(),2,4)."jpg";
		$token['fileprev'] = $this->qbox->GetDownloadURL($obj->hash);
		log_message('error','$token '.$token['fileprev']);
		$this->load->view('upload_form',$token);
	}
	public function uploadify()
	{
		$config['upload_path'] = "./uploads";
		$config['allowed_types'] = '*';
		$config['max_size'] = 0;
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload("userfile"))
		{
			$error = $this->upload->display_errors();
			var_dump($this->upload->data());
			var_dump($error);
		}
		else
		{
			$data = $this->upload->data();

			var_dump($data);
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

		        # 然后检查有效性
		        if($filekey == ""){
		            $resp = json_encode(array("code" => 400, "data" => array("errmsg" => "Invalid Params, <file_key> and <file_name> cannot be empty")));
		            die($resp);
		        }
		        $previewURL = $this->qbox->GetDownloadURL($filekey);
				log_message('error','$previewURL '.$previewURL);
				// $delURL=$this->qbox->DeleteQiniuFile($filekey);
		        # 最后返回处理结果
		        if (isset($previewURL)) {
		            die(json_encode(array(	"code" => 200,
		            						"preview"=>$previewURL, 
		            						// "deleteurl"=>$delURL, 
		            						"data" => array("success" => true))));
		        } else {
		            die(json_encode(array("code" => 499, "data" => array("errmsg" => "Insert Failed"))));
		        }
		        break;
		   	case "show":
		   		$previewURL1="";
		   		$previewURL2="";
		        $filekey1 = isset($_POST["file1_key"]) ? trim($_POST["file1_key"]) : "";
				$filekey2 = isset($_POST["file2_key"]) ? trim($_POST["file2_key"]) : "";
		        if($filekey1 != ""){
		            $previewURL1 = $this->qbox->GetDownloadURL($filekey1);
		        }
		        if($filekey2 != ""){
		            $previewURL2 = $this->qbox->GetDownloadURL($filekey2);
		        }
		        log_message('error','message show ('.$previewURL1.")(".$previewURL2.")");
		        # 最后返回处理结果
	            die(json_encode(array(	"code" => 200,
	            						"preview1"=>$previewURL1, 
	            						"preview2"=>$previewURL2, 
	            						"data" => array("success" => true))));
		        break;
		    case "show_huodong":
		   		$previewURL1="";
		   		$previewURL2="";
		   		$resPic="";
		        $filekey1 = isset($_POST["file1_key"]) ? trim($_POST["file1_key"]) : "";
				$filekey2 = isset($_POST["file2_key"]) ? trim($_POST["file2_key"]) : "";
				$id = isset($_POST["id"]) ? trim($_POST["id"]) : "";
		        if($filekey1 != ""){
		            $previewURL1 = $this->qbox->GetDownloadURL($filekey1);
		        }
		        if($filekey2 != ""){
		            $previewURL2 = $this->qbox->GetDownloadURL($filekey2);
		        }
		       	if($id != ""){
					$resPic=$this->huodong_model->getPic($id);
					$content_count = count($resPic);

					for ($i=0; $i <$content_count ; $i++) { 
						$resPic[$i]['hash']=$resPic[$i]['file_key'];
						$resPic[$i]['preview']=$this->qbox->GetDownloadURL($resPic[$i]['file_key']);
						log_message('error','message '.$resPic[$i]['hash']." ".$resPic[$i]['preview']);
					}
		        }

		        log_message('error','message show ('.$previewURL1.")(".$previewURL2.")");
		        # 最后返回处理结果
	            die(json_encode(array(	"code" => 200,
	            						"preview1"=>$previewURL1, 
	            						"preview2"=>$previewURL2, 
	            						"pic" =>$resPic,
	            						"data" => array("success" => true))));
		        break;
		    case 'delete':
		    	# code...
				$filekey = isset($_POST["file_key"]) ? trim($_POST["file_key"]) : "";
				log_message('error',' delete message '.$filekey);
				$this->qbox->DeleteQiniuFile($filekey);
	            die(json_encode(array(	"code" => 200,
						"data" => array("success" => true))));
		    	break;
		    # 如果是未知操作，返回错误
		    case 'update_delete':
		    	# code...
				$filekey = isset($_POST["file_key"]) ? trim($_POST["file_key"]) : "";
				$id = isset($_POST["id"]) ? trim($_POST["id"]) : "";
				$dbname=isset($_POST["dbname"]) ? trim($_POST["dbname"]) : "";
				$type=isset($_POST["type"]) ? trim($_POST["type"]) : "";
				log_message('error','message '.$dbname);
				switch ($dbname) {
					case 'hhs_news':
						// $this->load->model('news_model');
						$this->news_model->delete_news_fkey($id,$type);
						break;
					case 'hhs_huodong':
						$this->huodong_model->delPic($filekey);
						break;
					default:
						# code...
						break;
				}
				log_message('error',' delete message '.$filekey);
				$this->qbox->DeleteQiniuFile($filekey);
	            die(json_encode(array(	"code" => 200,
						"data" => array("success" => true))));
		    	break;
		    case 'getpic':
		    	$id = isset($_POST["id"]) ? trim($_POST["id"]) : "";
		    	log_message('error','$id'.$id);
		    	$resPic=$this->huodong_model->getPic($id);
		    	$content_count = count($resPic);

				for ($i=0; $i <$content_count ; $i++) { 
					$resPic[$i]['id']=$this->qbox->GetDownloadURL($resPic[$i]['file_key']);
					//log_message('error','message '.$resPic[$i]['id']);
				}
				$string = json_encode(array("code" => 200,"data" =>$resPic));
			//	log_message('error','string'.$string);
	            die($string );

		    # 如果是未知操作，返回错误
		    default:
		        $resp = json_encode(array("code" => 400, "data" => array("errmsg" => "Invalid URL, Unknow <action>: $act")));
		        die($resp);
		}
		# code...
	}
}

/* End of file uploadify.php */
/* Location: ./application/controllers/uploadify.php */