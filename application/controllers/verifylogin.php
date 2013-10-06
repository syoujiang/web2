<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verifylogin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','',TRUE);
		$this->load->helper('form');
		$this->load->helper('url');
	}

	public function index()
	{
		//This method will have the credentials validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

		if($this->form_validation->run() == FALSE)
		{
			//Field validation failed.&nbsp; User redirected to login page
			$this->load->view('login_view');
		}
		else
		{
			//Go to private area
			redirect('news', 'refresh');
		}
	}
	function check_database($password)
	{
		//Field validation succeeded.&nbsp; Validate against database
		$username = $this->input->post('username');

		//query the database
		$result = $this->user_model->login($username, $password);

		if($result)
		{
			$sess_array = array();
			foreach($result as $row)
			{
				$sess_array = array(
									'role' =>'admin'
									);
				$this->session->set_userdata('user', $sess_array);
			}
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}
}

/* End of file verifylogin.php */
/* Location: ./application/controllers/verifylogin.php */