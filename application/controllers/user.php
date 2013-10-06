<?php 
/**
 * 
 */
 class User extends CI_Controller
 {
 	
 	public function __construct()
 	{
 		parent::__construct();
 		//Do your magic here
 		$this->load->helper('form');
		$this->load->helper('url');
 	}
 } 
 ?>