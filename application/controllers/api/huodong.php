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
class Huodong extends REST_Controller
{
    private $show_count=0;
    function __construct()
    {
        # code...
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('huodong_model');
        $this->load->model('gonggao_model');
        $this->load->database();
        $this->load->helper('url');
        $this->show_count = $this->config->item('news_limit_no');
    }
    public function index_get()
    {
        $this->output->enable_profiler(TRUE);
        $sendmsg=$this->huodong_model->get_api(); 
        $this->response($sendmsg, 200); // 200 being the HTTP response code

    }
    function array_to_object($array) {
        return (object)$array;
    }
    //获取资讯内容
    function content_one_get()
    {
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
        $id = $this->get('id');


        $content=$this->huodong_model->getOne_api($id);
        $gonggao= $this->gonggao_model->getOne_api($id);
        if($content && $gonggao)
        {
            $huodong = array('id' => $gonggao['id'],
                        'title'=> $gonggao['title'], 
                        'gg_date'=> $gonggao['gg_date'],
                        'jihe_date'=> $gonggao['jihe_date'],
                        'didian'=> $gonggao['didian'],
                        'zhuchi'=> $gonggao['zhuchi'],
                        'renyuan'=> $gonggao['renyuan'],
                        'jijin'=> $content['jijin'],
                        'mingxi'=> $content['mingxi_phone'],
                        'mingxi_url'=> $content['mingxi_url'],
                        'mingxi_fkey'=> $content['mingxi_fkey'],
                        'mingxi_fname'=> $content['mingxi_fname'],
                        'gongde'=> $content['gongde_phone'],
                        'gongde_url'=> $content['gongde_url'],
                        'gongde_fkey'=> $content['gongde_fkey'],
                        'gongde_fname' => $content['gongde_fname']);

        $sendmsg = array('bucket' => "hhs",
                    'gonggao' => $huodong);
        $this->response($sendmsg, 200); // 200 being the HTTP response code
    }
    else
    {
        $this->response(array(), 200);
    }

    }  
}