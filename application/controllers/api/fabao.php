<?php defined('BASEPATH') OR exit('No direct script access allowed');

// namespace D;

/**
 * fabao
 *
 * This is an fabao of a few basic content interaction methods you could use
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
class fabao extends REST_Controller
{
    private $show_count=0;
    function __construct()
    {
        # code...
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('fabao_model');
        $this->load->model('user_model');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('qbox');
        $this->show_count = $this->config->item('news_limit_no');
    }
    public function index_get()
    {
        // 轮播图
        // 法宝分类
        $this->output->enable_profiler(TRUE);
        $types=$this->fabao_model->get_all_fabao_type();
       // $types=$this->fabao_model->get_tuijian_type();
        $lunbo=$this->fabao_model->get_lunbo_api();
        $sendmsg = array('bucket' => "hhs",
                        'lunbotu' => $lunbo,
                        'types' => $types);
        $this->response($sendmsg, 200); // 200 being the HTTP response code

        # code...
    }
    // 获取法宝类别
    function fabao_type_get()
    {
        $this->output->enable_profiler(TRUE);
        if($this->get('id'))
        {
            $types=$this->fabao_model->get_fabao_type($this->get('id'));
        }
        else
        {
            $types=$this->fabao_model->get_all_fabao_type();
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

        //法宝数量不多。一次都传递过去
        $content=$this->fabao_model->get_fabao_all_api($type_id);
        $sendmsg = array();
        $i=0;
        foreach ($content as $rows)  
        {  
            $rows['summary_url']=$this->qbox->GetDownloadURL($rows['summary_fkey']);
            $sendmsg[$i]=$rows;
            $i++;
        }  
        $sendmsg2 = array('bucket' => "hhs",
            'fabao' => $sendmsg);
        $this->response($sendmsg2, 200); // 200 being the HTTP response code
    }
    //获取资讯内容
    function content_one_get()
    {
        $this->db->select('info_iphone');
        $query = $this->db->get('hhs_fabao_yufei');
        $yunfei= $query->row_array();
        if(!$this->get('id'))
        {
            $sendmsg = array('bucket' => "hhs",
            'fabao' => array(),
            'tuijian' => array(),
            'yunfei' => $yunfei);
            $this->response($sendmsg, 200); // 200 being the HTTP response code
        }

        $this->output->enable_profiler(TRUE);
        $content=$this->fabao_model->getOneFabao_api($this->get('id'));      

        if($content)
        {
            $content_tuijian=$this->fabao_model->get_tuijian($content['type']);
            
            $content['summary_url']=$this->qbox->GetDownloadURL($content['summary_fkey']);
  
            $sendmsg2 = array();
            $i=0;
            foreach ($content_tuijian as $rows)  
            {  
                $rows['summary_url']=$this->qbox->GetDownloadURL($rows['summary_fkey']);
                $sendmsg2[$i]=$rows;
                $i++;
            }  
            $sendmsg3 = array('bucket' => "hhs",
                                'fabao' => $content,
                                'tuijian' => $sendmsg2,
                                'yunfei' => $yunfei);
            $this->response($sendmsg3, 200); // 200 being the HTTP response code
        }
        else
        {

            $sendmsg = array('bucket' => "hhs",
                            'fabao' => array(),
                            'tuijian' => array(),
                            'yunfei' => $yunfei);
            $this->response($sendmsg, 200); // 200 being the HTTP response code
        }

    }

    public function search_get()
    {
        $likename=$this->get('name');
        $fabao_msg=$this->fabao_model->get_fabao_search($likename);
        if($fabao_msg)
        {
            $sendmsg = array('bucket' => "hhs",
                    'fabao' => $fabao_msg);
        }
        else
        {
            $sendmsg = array();
        }
        $this->response($sendmsg, 200); // 200 being the HTTP response code
    }

    public function tj_fabao_type_get()
    {
        # code...
        $fabao_msg=$this->fabao_model->get_tuijian_type();
        $this->response($fabao_msg, 200); // 200 being the HTTP response code


    }
    public function tj_fabao_get()
    {
        # code...
        $fabao_msg=$this->fabao_model->get_tuijian($this->get('type'));
        $sendmsg2 = array();
        $i=0;
        foreach ($fabao_msg as $rows)  
        {  
            $rows['summary_url']=$this->qbox->GetDownloadURL($rows['summary_fkey']);
            $sendmsg2[$i]=$rows;
            $i++;
        } 
        $sendmsg = array('bucket' => "hhs",
                'fabao' => $sendmsg2);
        $this->response($sendmsg, 200); // 200 being the HTTP response code
    }

    public function shop_get()
    {
        $username = $this->input->server('PHP_AUTH_USER');
        # code...
        $message = $this->fabao_model->GetFabao_api($username);
        $this->response($message, 200); // 200 being the HTTP response code
    }

    // 增加法宝,放入购物车
    function shop_post()
    {
        if(!$this->post('number'))
        {
            $message = array('result' => '0',
                            'reason' => '没有法宝数量');
            $this->response($message, 200); // 200 being the HTTP response code
            return;
        }
        // if(($this->post('number') > 5)|| ($this->post('number') < 1)){
        //     # code...
        //     $message = array('result' => '0',
        //         'reason' => '一次添加的法宝数量不能超过5个');
        //     $this->response($message, 200); // 200 being the HTTP response code
        //     return;
        // }
        $ret = $this->fabao_model->AddFabao_api($this->post('user'),
                                        $this->post('id'),
                                        $this->post('number'));
        if($ret)
        {
            log_message('error','AddFabao_api'.$this->post('id'));
            $message = array('result' => '1',
            'reason' => "添加成功");
            $this->response($message, 200); // 200 being the HTTP response code
        }
        else
        {
            $message = array('result' => '0',
            'reason' => "结缘车已经存在这个法宝，请不要重复添加！");
            $this->response($message, 200); // 200 being the HTTP response code

        }

    }
    public function shop_put()
    {
        if(!$this->put('number'))
        {
            $message = array('result' => '0',
                            'reason' => '没有法宝数量');
            $this->response($message, 200); // 200 being the HTTP response code
            return;
        }
        // if(($this->put('number') > 5)|| ($this->put('number') < 1))
        // {
        //     # code...
        //     $message = array('result' => '0',
        //         'reason' => '一次添加的法宝数量不能超过5个');
        //     $this->response($message, 200); // 200 being the HTTP response code
        //     return;
        // }
        $ret = $this->fabao_model->UpdateFabao_api($this->put('user'),
                                        $this->put('id'),
                                        $this->put('number'));
        if($ret)
        {
            $message = array('result' => '1',
            'reason' => "更新成功");
            $this->response($message, 200); // 200 being the HTTP response code
        }
        else
        {
            $message = array('result' => '0',
            'reason' => "请先添加法宝");
            $this->response($message, 200); // 200 being the HTTP response code

        }
    }

    public function shop_delete()
    {
        if($this->delete('id'))
        {
            log_message('error','delete_shop'.$this->delete('id'));
            $this->fabao_model->delete_shop($this->delete('user'),$this->delete('id'));
        }
        else
        {
            log_message('error','empty_shop');
            $this->fabao_model->empty_shop($this->delete('user'));
        }
        $message = array('result' => '1',
            'reason' => "删除成功");
        $this->response($message, 200); // 200 being the HTTP response code
    }

    // 提交订单
    function order_post()
    {
        $errmsg="";
        $user=$this->post('user');
        $fabao=$this->post('fabao');
        $fabao = json_decode($fabao,true);
        log_message('error',$fabao);
        if($this->fabao_model->create_order($user,$fabao,$errmsg))
        {
            $message = array('result' => '1','reason'=>$errmsg);
            $this->response($message, 200); // 200 being the HTTP response code
        }
        else
        {
            $message = array('result' => '0','reason'=>$errmsg);
            $this->response($message, 200); // 200 being the HTTP response code
         }
    }

    //获取订单
    public function order_get()
    {
        # code...
        if(!$this->get('id'))
        {
            $this->response(array(), 200);
        }
        $this->output->enable_profiler(TRUE);
        $content=$this->fabao_model->get_one_order($this->get('id'));
        $content_count = count($content);
        for ($i=0; $i <$content_count ; $i++) { 
            $content[$i]['fabao_id']=$this->fabao_model->getFbName($content[$i]['fabao_id']);
        }
        if($content)
        {
            $this->response($content, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array(), 200);
        }
    }

    function content_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    

}