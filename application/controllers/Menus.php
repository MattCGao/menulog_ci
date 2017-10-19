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
class Menus extends CI_Controller {

	
	/***
	 * Print the view data,
	 * check all the data, then pass them to the view.
	 * 
	 * @param	$data		mixed value.
	 *
	 */	
	private function print_view_data($data)
	{
		$Restaurant = $data['Restaurant'];
		$Menus	= $data['Menus'];
		
		echo "<table>";
		echo "<tr>";
		echo '<td width=40%><a href="https://www.just-eat.co.uk/restaurants-'.$Restaurant->UniqueName .'/menu"><img src="'.$Restaurant->Logo[0]->StandardResolutionURL.'" alt="'.$Restaurant->Name.' " class="img-responsive" > </a></td>';
		echo '<td width=60%><h6>'.$Restaurant->Name .'</h6><h6>'. $Restaurant->Address. '</h6><h6>Rating Average: '. $Restaurant->RatingAverage.'</h6></td>';
		echo "</tr>";
		
		foreach ($Menus as $m)
		{
			echo "<tr>";
			echo "<td><bold>" .$m->Title ."</bold></td>";
			echo "</tr>";
			 
			//1. Catagroies
			
			foreach ($m->categories_set as $cats)
			{
				foreach ($cats->Categories as $c)
				{
					echo "<tr>";
					echo "<td><bold>". $c->Name."</bold></td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>". $c->Notes."</td>";
					echo "</tr>";
			
					//2. products.
					foreach ($c->products_set as $products)
					{
						foreach ($products->Products as $p)
						{
							echo "<tr>";
							echo "<td><bold>" .$p->Name ."</bold></td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>" .$p->Description ."</td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>" .$p->Synonym ."</td>";
							echo "<td> $ " .$p->Price ."</td>";
							echo "</tr>";
						}
					}
				}	
			}
		
		}
		
		echo "</table>";
		
	}
	
	
	/***
	 * Prepare the view data,
	 * then pass them to the view.
	 * 
	 * @param	$menus			array
	 * @param	$restaurantid	string
	 * 
	 */	
	private function prepare_view_data($menus, $restaurantid)
	{
		$data->Menus = $menus;
		$data->Restaurant = $this->curldblib->getRestaurantsById($restaurantid);
				
		return $data;
	}
	
	private function generateMenusListView($data)
	{
		$this->load->view('menus/MenusList', $data);
	}
	
	/***
	 * Prepare the class
	 * load helper-url, session, CurlDblib library.
	 *
	 * @param	$menus			array
	 * @param	$restaurantid	string
	 *
	 */	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->helper('url');
	
// 		$this->load->library('session');
		$this->load->library('CurlDblib');
		if(!isset($_SESSION['menus']))
		{
			$_SESSION['menus'] = array();
		}
		if(!isset($_SESSION['restaurants']))
		{
			$_SESSION['restaurants'] = array();
		}	
	}
	
	
	/***
	 * Pick up the restaurant id,
	 * get all menus, catagories, products in the area.
	 * pass the menus info to the view.
	 *
	 */
	public function Index($restaurantid)
	{
		// Pick up all things.
		
		//1. Convert to upper case.
		$restaurantid = strtoupper($restaurantid);		
		
		//2. Judge whether show simple info or show whole info.
		if(!strcmp($this->uri->segment(4),('getMenusList')))
		{
			$str = $this->uri->segment(5);
			$str_postcode = $this->uri->segment(6);
			$this->getMenusList($str, $str_postcode);
		}
		else
		{

			$data['restaurantid'] = $restaurantid;
 			$data['postcode'] = $this->uri->segment(4);
			$this->load->view('menus/index', $data);
		}
	}	
	
	public function getMenusList($restaurantid, $postcode)
	{	

		//1. Pick up all things, and put into session->menus.
		$result = $this->curldblib->getMenus($restaurantid);
		
		// 		echo var_dump($result);
		
		$this->curldblib->getRestaurantsByPostcode($postcode);
		
		$data = array();
		$data['Restaurant'] = $this->curldblib->getRestaurantsById($restaurantid);
		$data['Menus'] = $result->Menus;
		
		// 		$this->print_view_data($data);
		
// 		$this->load->view('menus/index', $data);		
		
		
		//4. Generate the restaurants list and return to the client.
		$this->generateMenusListView($data);
	
	}
	
}