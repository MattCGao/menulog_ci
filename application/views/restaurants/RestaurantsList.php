

  <div class="row">
  <div class="col-xs-6 col-xs-offset-2">
  
  
  <div class="table-responsive">
  <?php 
        if(!isset($Restaurants))
        {
        	echo '<tr>';
        	echo '<td colspan="2"  style="background-color:#e80202"> Sorry, there is no product now, please check later. |<a href="javascript:void(0)" onclick="history.back(0)"> Back </a></td>';
        	echo '</tr>';
        }
        else {
  ?>
  <h5>There are <?php echo count($Restaurants)?> takeaway restaurants in 
  		<?php 
  		if(isset($postcode))
  		{
  			echo ' '. $postcode;
  		}
  		if(isset($Area))
  		{
  			echo '  '.$Area;
  		}
  		
  		echo ' |<a href="javascript:void(0)" onclick="history.back(0)"> Back </a>';
  		?>.
  </h5>
  <table class="table table-striped">
    <tbody>
    <?php 
	     	foreach ($Restaurants as $res)
	     	{     		
	     		echo  "<tr>";
	     		
	     		echo '<td width=40%><a href="https://www.just-eat.co.uk/restaurants-'.$res->UniqueName .'/menu"><img src="'.$res->Logo[0]->StandardResolutionURL.'" alt="'.$res->Name.' " class="img-responsive" > </a></td>';
	     		echo '<td width=60%><h6>'.$res->Name .'</h6><h6>'. $res->Address. '</h6><h6>Rating Average: '. $res->RatingAverage.'</h6><a href="'.base_url('index.php/Menus/index').'/'.$res->Id .'/'.$postcode.'"><input type="button" value="See Menu" /></td>';

	     		echo '</tr>';     		
	     	}
        }

    ?>
    </tbody>
  </table>
  </div>  
  
  
  </div>

  
  </div> <!-- row -->
  
