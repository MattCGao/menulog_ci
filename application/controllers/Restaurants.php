<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start();
?>
<?php 

/**
 *
 * Show restaurants according to the postcode.
 *
 * @category	application/controller
 * @author 		gaochunlei
 **/
class Restaurants extends CI_Controller {
	
	/***
	 * Prepare the view data,
	 * then pass them to the view.
	 *
	 * @param	$restaurants		array
	 * @param	$sort_type			'asc', 'desc'
	 *
	 * @return  $restaurants 
	 */
	private function prepare_view_data($restaurants, $sort_key, $sort_type=SORT_DESC)
	{
		if(!isset($restaurants) || (!isset($sort_key)))
		{
			return $restaurants;
		}
		
		foreach ($restaurants as $r)
		{
			$ra[] = $r->RatingAverage;
		}
				
		if(isset($ra))
		{
			array_multisort($ra, $sort_type, $restaurants);
		}
		
		return $restaurants;
	}
		
	private function generateRestaurantsListView($result)
	{
		$this->load->view('restaurants/RestaurantsList', $result);		
	}
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		
// 		$this->load->library('session');
		$this->load->library('CurlDblib');
		
		if(!isset($_SESSION['restaurants']))
		{
			$_SESSION['restaurants'] = array();
		}
		if(!isset($_SESSION['menus']))
		{
			$_SESSION['menus'] = array();
		}
	}
	
	/***
	 * Pick up the postcode, 
	 * get all restaurants in the area.
	 * pass the restaurants info to the view.
	 * 
	 */
	public function Index($postcode)
	{
		//1. Convert to upper case.
		$data['postcode'] = strtoupper($postcode);
		
		//2. Judge whether show simple info or show whole info.
		if(!strcmp($postcode,'getRestaurantList'))
		{
			$str = $this->uri->segment(4);
			$this->getRestaurantList($str);
		}
		else 
		{		
			$this->load->view('restaurants/index', $data);
		}
		
	}
	
	public function getRestaurantList($postcode)
	{
		
		//1. Convert to upper case.		
		$postcode = strtoupper($postcode);
		
		//2. Get the orginal data.
		$result = $this->curldblib->getRestaurantsByPostcode($postcode);
		$result->postcode = $postcode;
		
		//3. Sort the set.
		$result->Restaurants = $this->prepare_view_data($result->Restaurants, 'RatingAverage', SORT_DESC);
		
		//4. Generate the restaurants list and return to the client.
		$this->generateRestaurantsListView($result);
		
	}
}
