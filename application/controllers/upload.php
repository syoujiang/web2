<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('qbox');
	}
	public function index()
	{
		$token['uptoken'] = $this->qbox->GetUploadURL();
		$token['filename'] =date('ymdHis').substr(microtime(),2,4)."jpg";
		$token['fileprev']="";
		$this->load->view('upload2',$token);
	}
	public function commit()
	{
		$this->qbox->uploadlocalfile($_FILES["file"]["name"]);
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
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		echo $_GET['code'];
		echo $_GET['upload_ret'];
		$qniu_res=$this->urlsafe_base64_decode($_GET['upload_ret']);
		$obj=json_decode($qniu_res);
		// $this->qbox->GetDownloadURL("hhshe.qiniudn.com",$obj->hash);
		$token['uptoken'] = $this->qbox->GetUploadURL();
		$token['filename'] =date('ymdHis').substr(microtime(),2,4)."jpg";
		$token['fileprev'] = $this->qbox->GetDownloadURL("hhshe.qiniudn.com",$obj->hash);
		$this->load->view('upload',$token);
	}
}

/* End of file upload.php */
/* Location: ./application/controllers/upload.php */