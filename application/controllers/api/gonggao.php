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
class Gonggao extends REST_Controller
{
    private $show_count=0;
    function __construct()
    {
        # code...
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('gonggao_model');
        $this->load->model('fabao_model');
        $this->load->model('user_model');
        $this->load->database();
        $this->load->helper('url');
        $this->show_count = $this->config->item('news_limit_no');
    }
    public function index_get()
    {
        // 轮播图
        // 法宝分类
        $this->output->enable_profiler(TRUE);
       // $types=$this->fabao_model->get_all_fabao_type();
        $types=$this->fabao_model->get_tuijian_type();
        $lunbo=$this->gonggao_model->get_lunbo_api();
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
            echo $this->get('method');
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
    }

    //获取资讯内容
    function content_one_get()
    {
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        $id = $this->get('id');


        $content=$this->gonggao_model->getOne_api($id);
        $sendmsg = array('bucket' => "hhs",
                    'gonggao' => $content);

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
        $sendmsg = array('bucket' => "hhs",
                'fabao' => $fabao_msg);
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
            $this->fabao_model->delete_shop($this->delete('user'),$this->delete('id'));
        }
        else
        {
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
        if($this->fabao_model->create_order($user, $errmsg))
        {
            $message = array('result' => '1','reason'=>$errmsg);
            $this->response($message, 200); // 200 being the HTTP response code
        }
        else
        {
            $message = array('result' => '0','reason'=>$errmsg);
            $this->response($message, 200); // 200 being the HTTP response code
        }
        // $message = $this->fabao_model->GetFabao_api($this->post('user'));

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
        var_dump($content);
        $content_count = count($content);
        for ($i=0; $i <$content_count ; $i++) { 
            # code...
            // echo $content[$i]['fabao_id'];
            $content[$i]['fabao_id']=$this->fabao_model->getFbName($content[$i]['fabao_id']);
        }
        var_dump($content);
        // foreach ($content as $value) {
        //     # code...
        //     foreach ($value as $key=>$age) {    
        //         if($key == "fabao_id"){
        //             foreach ($fabao_name as $key => $value) {
        //                 # code...
        //             }
        //             echo $age;
        //             echo $fabao_name['$age'];
        //         }
        //     }
        // }

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