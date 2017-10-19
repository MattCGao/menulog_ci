
  <div class="row">
  <div class="col-xs-6 col-xs-offset-2">
       
  <div class="table-responsive">

  <table class="table">
    <tbody>
    <?php 
        if(isset($Restaurant))
        {
	        echo '<tr style="background-color:#e1dcdb">';
	      	echo '<td width=40%><a href="https://www.just-eat.co.uk/restaurants-'.$Restaurant->UniqueName .'/menu"><img src="'.$Restaurant->Logo[0]->StandardResolutionURL.'" alt="'.$Restaurant->Name.' " class="img-responsive" > </a></td>';
	     	echo '<td width=60%><h6>'.$Restaurant->Name .'</h6><h6>'. $Restaurant->Address. '</h6><h6>Rating Average: '. $Restaurant->RatingAverage.' |<a href="javascript:void(0)" onclick="history.back(0)"> Back </a></h6></td>';
	    	echo "</tr>";
        }
        else 
        {
        	echo '<tr style="background-color:#e1dcdb">';
        	echo '<td colspan="2"> <h6>You are welcome!  | <a href="javascript:void(0)" onclick="history.back(0)"> Back </a></h6></td>';
        	echo "</tr>";
        }
    	
    	if(!isset($Menus))
    	{
    		echo '<h6> There is no product in this store, please check later. </h6>';
    	}
    	else 
    	{
	     	foreach ($Menus as $m)
	     	{
	     		echo "<tr>";
	     		echo '<td colspan="2" style="background-color:#d2e3e1"><b>' .$m->Title .'</b></td>';
	     		echo "</tr>";
	     	
	     		//1. Catagroies
	     			
	     		foreach ($m->categories_set as $cats)
	     		{
	     			foreach ($cats->Categories as $c)
	     			{
	     				echo "<tr>";
	     				echo '<td colspan="2" style="background-color:#8c828b"><b>'. $c->Name.'</b></td>';
	     				echo "</tr>";
	     				echo "<tr>";
	     				echo '<td colspan="2">'. $c->Notes.'</td>';
	     				echo "</tr>";
	     					
	     				//2. products.
	     				foreach ($c->products_set as $products)
	     				{
	     					foreach ($products->Products as $p)
	     					{
	     						
	     						if($p->Synonym == null)
	     						{
		     						echo '<tr style="background-color:#d2e3e1">';
		     						echo '<td><b>' .$p->Name .'</b></td>';
	     							echo '<td style="text-align:left"> $ ' .$p->Price .'</td>';
	     							echo "</tr>";
	     							if($p->Description != null)
	     							{
	     								echo "<tr>";
	     								echo '<td colspan="2">' .$p->Description .'</td>';
	     								echo "</tr>";     					
	     							}
	     						}
	     						else {
		     						echo "<tr>";
		     						echo '<td colspan="2" style="background-color:#d2e3e1"><b>' .$p->Name .'</b></td>';
		     						echo "</tr>";
		     						if($p->Description != null)
		     						{
	     								echo "<tr>";
		     							echo '<td colspan="2">' .$p->Description .'</td>';
		     							echo "</tr>";
		     						}
		     						echo "<tr>";
		     						echo "<td>" .$p->Synonym ."</td>";
		     						echo '<td style="text-align:left"> $ ' .$p->Price .'</td>';
		     						echo "</tr>";
	     						}
	     					}
	     				}
	                    
	                    echo '<tr><td colspan="2"></td></tr>';
	     			}
	                echo '<tr><td colspan="2"></td></tr>';
	     		}
	     	
	     	}
    	}
    ?>
    </tbody>
  </table>
  </div>  
  
  
  </div>

  
  </div>