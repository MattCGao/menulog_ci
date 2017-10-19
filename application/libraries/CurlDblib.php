<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php 
/**
 * Define a special db class to access the remote data 
 * via curl method.
 * 
 * implement:
 * 1. set curl options
 * 2. provide 3 get methods to return data.
 * 3. cache the data for the future.
 * 
 * 
 * @author	Chunlei Gao
 * 
 * 
 * */

class CurlDblib{

	/**
	 * private variable
	 *
	 * @name 	$ch
	 * @desc	store curl handle.
	 *
	 * */	
	private $ch;
	
	
	/**
	 * private variable 
	 * 
	 * @name 	$restaurants
	 * @desc	store data as array($postcode => $restaurants).
	 * 			so, if you have a valid $postcode, then you can get all restaurants.
	 * 			Notice: this $restaurants is stored in _SESSION['restaurants']. 
	 *                  As we cannot use CodeIgniter Library - session, so we just use the reference.
	 *                  this _SESSION['restaurants'] will created in Restaurants.php.
	 * 
	 * */
	
	private $restaurants;
	
	/**
	 * private variable
	 *
	 * @name 	$menus
	 * @desc	store data as array($restaurant_id => $menus )
	 * 							$menus : array($menustype -- often are 'Delivery' 'Collection' types)
	 * 							$menustype : array($productcategories -- means this menu owns the number of categories)
	 * 							$productcategories: array($products -- means this category own the all products)
	 * 			so, if you have a valid $restaurant_id, then you can get all menus, categories and products in one time.
	 * 			Notice: this $menus is stored in _SESSION['menus']. 
	 *                  As we cannot use CodeIgniter Library - session, so we just use the reference.
	 *                  this _SESSION['menus'] will created in Menus.php.
	 *                      
	 * 				
	 * */	
	
	private $menus;

	/**
	 * 
	 * set curl options.
	 * 
	 * @param	$url		the url of request.
	 *
	 * */	
	private function set_curl_opt($url)
	{
		if(!isset($this->ch))
		{
			$this->ch = curl_init();
		}
		
		$headers = array("Accept-Tenant: uk", "Accept-Language: en-GB","Accept-Charset: utf-8","Authorization: Basic VGVjaFRlc3RBUEk6dXNlcjI=","Host: public.je-apis.com");
		
 		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($this->ch, CURLOPT_HTTPGET, true);
		
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
		
		curl_setopt($this->ch, CURLOPT_TIMEOUT, 600);
		curl_setopt($this->ch, CURLOPT_HEADER, 0);
		
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($this->ch, CURLOPT_BUFFERSIZE, 8192);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);		
	}
	
	/**
	 *
	 * check whether restaurants is existed in cache.
	 *
	 * @param	$postcode		the postcode of restaurants.
	 * @return  null			if not found
	 * 			Restaurants		if found.
	 *
	 * */	
	private function  cached_restaurants_postcode($postcode)
	{
		if(empty($this->restaurants))
		{
			return null;
		}
		
		foreach ($this->restaurants as $r)
		{
			foreach ($r as $key=>$val)
			{
				if(strtoupper($key) == strtoupper($postcode))
				{
					return $val;
				}
			}
		}
		
		return null;
	}

	
	/**
	 *
	 * check whether restaurants is existed in cache.
	 *
	 * @param	$restaurant_id	the id of restaurants.
	 * @return  null			if not found
	 * 			Restaurants		if found.
	 *
	 * */
	private function  cached_restaurants_id($restaurant_id)
	{
		if(empty($this->restaurants))
		{
			return null;
		}
	
		foreach ($this->restaurants as $ra)
		{
			foreach ($ra as $rs)
			{
				foreach ($rs->Restaurants as $r)
				{
	
					if($r->Id == $restaurant_id)
					{
						return $r;
					}
				}
			}
		}
	
		return null;
	}	
	
	
	/**
	 *
	 * check whether restaurantid is existed in cache.
	 *
	 * @param	$restaurantid		the id of restaurants.
	 * @return  null				if not found
	 * 			menus				if found.
	 *
	 * */	
	private function cached_menus($restaurantid)
	{
		if(empty($this->menus))
		{
			return null;
		}
		
		foreach ($this->menus as $m)
		{
			foreach ($m as $key=>$val)
			{
				if($key == $restaurantid)
				{
					return $val;
				}
			}
		}
		
		return null;
	}

	/**
	 *
	 * construct.
	 *
	 *
	 * */	
	public function __construct()
	{
		$this->ci = & get_instance();		
	}	
	
	/**
	 *
	 * get restaurants infomation.
	 * first check the cache,
	 * if not find, then get remote data.
	 * 
	 * cache: now is just use _SESSION['restaurants'].
	 *
	 * @param	$postcode		the postcode of restaurants.
	 * @return  null			if not found
	 * 			Restaurants		if found.
	 *
	 * */	
	public function getRestaurantsByPostcode($postcode)
	{
		$res = null;
		
		if(!empty($postcode))
		{
			// Get the reference of session['restaurants']. 
			$this->restaurants = &$_SESSION['restaurants'];
			
			$res = $this->cached_restaurants_postcode($postcode);
			
			if($res == null)
			{
				$url = "https://public.je-apis.com/restaurants?q=".$postcode;
				$this->set_curl_opt($url);
		
				//1. Get Restaurants Array.
				$res = curl_exec($this->ch);
				
// 				curl_close($this->ch);
				
				if($res != null)
				{
					$res = json_decode($res);
									
					array_push($this->restaurants, array($postcode => $res));					
				}						
			}			
		}

		return $res;		
	}
	
	/**
	 *
	 * get restaurants infomation.
	 * first check the cache,
	 * if not find, do NOT get remote data.
	 * for something maybe wrong.
	 *
	 * cache: now is just use _SESSION['restaurants'].
	 *
	 * @param	$restaurant_id		the postcode of restaurants.
	 * @return  null			if not found
	 * 			Restaurants		if found.
	 *
	 * */
	public function getRestaurantsById($restaurant_id)
	{
		$res = null;
		
		if(!empty($restaurant_id))
		{
			$this->restaurants = &$_SESSION['restaurants'];
			
			$res = $this->cached_restaurants_id($restaurant_id);			
		}
		
		return $res;
	}

	/**
	 *
	 * get all menus, catagories, products of the $restaurantid.
	 * first check the cache,
	 * if not find, then get remote data.
	 *
	 * cache: now is just use _SESSION['menus'].
	 *
	 * @param	$restaurantid		the id of restaurants.
	 * @return  null				if not found
	 * 			menus			if found.
	 *
	 * */	
	private function get_remote_menus($restaurantid)
	{
		//1. get array($restaurantid => $menus)
		//1.1 if use this command, will return all catagories.
// 		$url = "https://public.je-apis.com/restaurants/". $restaurantid ."/menus?delivery=&current=&postcode=";

		//1.1 if use this command, will only return the first catagory.
		//    so in the future, we should use the above command to get all information.
		$url = "https://public.je-apis.com/restaurants/". $restaurantid ."/menus";
		$this->set_curl_opt($url);
		
		$result = curl_exec($this->ch);
		
		if($result == null)
		{
			return null;
		}
		
		$result = json_decode($result);

		array_push($this->menus, array($restaurantid => $result));
		
		//2. get array($categories)
		$menus = $result->Menus;
		foreach ($menus as $m)
		{
			//2.1 get ID.
			$menus_id = $m->Id;
			
			//2.2 generate url.
			$url = "http://public.je-apis.com:80/menus/".$menus_id."/productcategories";
			$this->set_curl_opt($url);
			
			//2.3 get all categories
			$res = curl_exec($this->ch);
			
			if($res == null)
			{
				continue;
			}
			
			//2.4 convert to json type 
			$res = json_decode($res);
			
			//2.5 save the categories.
			if(!isset($m->categories_set))
			{
				$m->categories_set = array();
			}
			array_push($m->categories_set, $res);
			
			//2.6 get all products of the current menuid.
			$categories = $res->Categories;
			foreach ($categories as $c)
			{
				//2.6.1 get id.
				$c_id = $c->Id;
				
				//2.6.2 generate url.
				$url = "http://public.je-apis.com:80/menus/".$menus_id."/productcategories/".$c_id."/products";
				$this->set_curl_opt($url);
				
				//2.6.3 get all products.
				$res = curl_exec($this->ch);
				
				if($res == null)
				{
					continue;
				}
				
				//2.6.4 convert data to json type.
				$res = json_decode($res);
				
				//2.6.5 save the products.
				if(!isset($c->products_set))
				{
					$c->products_set = array();
				}
				array_push($c->products_set, $res);
			}
		}
		
// 		curl_close($this->ch);
		
		return $result;
	}
	
	/**
	 *
	 * get the restaurant's menus infomation.
	 * first check the cache,
	 * if not find, then get remote data.
	 * 
	 * cache: now is just use _SESSION['menus'].
	 *
	 * @param	$restaurantid	the id of restaurants.
	 * @return  null			if not found
	 * 			Restaurants		if found.
	 *
	 * */	
	public function getMenus($restaurantid)
	{
		if(!empty($restaurantid))
		{
			// Get the reference of session['menus'].
			$this->menus = &$_SESSION['menus'];
			
			$res = $this->cached_menus($restaurantid);
				
			if($res == null)
			{
				$res = $this->get_remote_menus($restaurantid);

			}
		
			return $res;
		}
		else
		{
			return null;
		}		
		
	}	
	
}