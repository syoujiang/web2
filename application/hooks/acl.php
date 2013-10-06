<?php
/**
 * This class will be called by the post_controller_constructor hook and act as ACL
 * 
 * @author ChristianGaertner
 */
class ACL {
    
    private $url_model;//所访问的模块，如：music  
    private $url_method;//所访问的方法，如：create  
    private $CI;  
      
    function Acl()  
    {  
        $this->CI = & get_instance();  
        $this->CI->load->library('session');  
        $url = $_SERVER['PHP_SELF'];  
        $arr = explode('/', $url);  
        $arr = array_slice($arr, array_search('index.php', $arr) + 1, count($arr));  
        $this->url_model = isset($arr[0]) ? $arr[0] : '';  
        $this->url_method = isset($arr[1]) ? $arr[1] : 'index';  
        $this->url_param = isset($arr[2]) ? $arr[2] : '';  
       // $this->url_model = $this->CI->router->fetch_class();
       // $this->url_method  = $this->CI->router->fetch_method();
    }  
      
    function filter()  
    {  
        $user = $this->CI->session->userdata('user');  
        log_message('debug','$this->url_model '.$this->url_model);
        if (($this->url_model != "login")&&($this->url_model != "verifylogin")&&($this->url_model != "api")) 
        {
            log_message('debug','empty($user)');
            if(empty($user))
            {
                redirect("login/index");
            }
        } 
        else 
        {  
            log_message('debug','message_----------------------------');
        }  
    }  
}

/* End of file acl.php */
/* Location: ./application/hooks/acl.php */