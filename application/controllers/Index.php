<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Welcome Class
 * Show the home page of the website.
 * provid the data to views/welcome/index.php
 * @category	applcation/controller
 * @author		Chunlei Gao
 *
 * */
class Index extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
	}

	/**
	 * Index Page for this controller.
	 *
	 * bind the views/welcome/index.php,
	 * show the page. 
	 * ask the customer to input the postcode.
	 * @param	none
	 * @return	none
	 *
	 * */
	public function index()
	{
		$this->load->view('welcome/index');
	}
}
