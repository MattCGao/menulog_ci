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
class Welcome extends CI_Controller {

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
