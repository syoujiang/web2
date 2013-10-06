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
class Yigong extends REST_Controller
{
    private $show_count=0;
    function __construct()
    {
        # code...
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('yigong_model');
        $this->load->database();
        $this->load->helper('url');
        $this->show_count = $this->config->item('news_limit_no');
    }

    function resource_post()
    {
        if($this->yigong_model->create_api($this->post('name'),
                                        $this->post('phone'),
                                        $this->post('mail'),
                                        $this->post('qq'))
        
        $message = array('result' => '1',
            'reason' => "添加成功");
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
   
}