<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//游客权限映射  
$config['acl']['visitor'] = array(  
	'' => array('login'),//首页  
	'music' => array('login', 'index'),  
	);  
	//管理员  
	$config['acl']['admin'] = array(  
	  
	);  
  
//-------------配置权限不够的提示信息及跳转url------------------//  
$config['acl_info']['visitor'] = array(  
	'info' => '需要登录以继续',  
	'return_url' => 'login/index'  
	);  
/* End of file acl.php */
/* Location: ./application/config/acl.php */
