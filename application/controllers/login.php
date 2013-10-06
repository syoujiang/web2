<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class Login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
	}

	public function index()
	{
		log_message('debug','message login index');
		//If no session, redirect to login page
		//redirect('login', 'refresh');
		$this->load->view('login_view');

	}
	function logout()
	{
		$this->session->unset_userdata('user');
		session_destroy();
		redirect('login/index', 'refresh');
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */