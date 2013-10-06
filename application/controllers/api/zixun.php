<?php defined('BASEPATH') OR exit('No direct script access allowed');

// namespace D;

/**
 * zixun
 *
 * This is an zixun of a few basic content interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
class zixun extends REST_Controller
{
    private $show_count=0;
    function __construct()
    {
        # code...
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('news_model');
        $this->load->model('user_model');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('qbox');
        $this->show_count = $this->config->item('news_limit_no');
    }
    // 获取资讯类别
    function types_get()
    {
        $this->output->enable_profiler(TRUE);
        if($this->get('id'))
        {
            echo $this->get('method');
            $types=$this->news_model->get_news_type($this->get('id'));
        }
        else
        {
            $types=$this->news_model->get_all_news_type_api();
        }
        if($types)
        {
            $this->response($types, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array(), 200);
        }
    }
    //获取资讯的摘要
	function content_get()
    {

        if(!$this->get('type_id'))
        {
        	$this->response(NULL, 400);
        }
        $type_id = $this->get('type_id');

        if($this->get('contentid'))
        {
            if(!$this->get('direct'))
            {
                $this->response(array(), 400);
            }
            else
            {
                $content=$this->news_model->get_news_limit_api($type_id,$this->get('contentid'),$this->get('direct'),$this->show_count); 
            }
        }
        else
        {
            $this->output->enable_profiler(TRUE);
            $content=$this->news_model->get_news_limit_api($type_id,0,'none',$this->show_count); 
        }

        if($content)
        {
            $sendmsg = array();
            $i=0;
            foreach ($content as $rows)  
            {  
                $rows['summary_url']=$this->qbox->GetDownloadURL($rows['summary_fkey']);
                $sendmsg[$i]=$rows;
                $i++;
            }  

            $this->response($sendmsg, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array(), 200);
        }
    }
    //获取资讯内容
    function content_one_get()
    {
        if(!$this->get('id'))
        {
            $this->response(array(), 200);
        }

        $this->output->enable_profiler(TRUE);
        $content=$this->news_model->getOneNews_api($this->get('id'));
        if($content)
        { 
            $content['content_url']=$this->qbox->GetDownloadURL($content['content_fkey']);
            $this->response($content, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array(), 200);
        }
    }


    function content_post()
    {
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function content_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function users_get()
    {
      //  echo "fk dennis";
        $user = $this->get('name');
        $passwd = $this->get('passwd');
        if((!$user) || (!$passwd))
        {
            $this->response(array(), 200);
        }
        else
        {
            $users = $this->user_model->check_User_api($user,$passwd);
            if($users)
            {
                $this->response($users, 200); // 200 being the HTTP response code
            }

            else
            {
                $this->response(array(), 200);
            }
        }

    }


	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}