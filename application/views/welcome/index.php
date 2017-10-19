<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php $this->load->view('common/header.php')?>
			
<!--   content -->
<main>



<div class="container-fluid" id="search-container">   

	<div class="row" id="search-row">
		<div class="col-xs-8 col-xs-offset-2" id="search-col">

			<h1>Order takeaway online</h1>
			<h5>Choose from over 20,000 takeaways</h5>
			
			<form class="form-horizontal" role="form" method="post" action="index.php/restaurants" id="search-form"">            
				<div class="form-group">
				<label class="control-label col-sm-4" for="postcode">Postcode:</label>
				<div class="col-sm-4">          
					<input type="text" class="form-control" id="postcode" name="postcode" placeholder="Input postcode to search" required>
				</div>
				<span class="invalid" id="invalid-text"></span>
				</div>
				<div class="col-sm-offset-4 col-sm-4"> 
						<button type="button" class="btn btn-primary btn-block" id="submit" name="submit" onclick="validate();">Find takeaways</button>
				</div>
			</form>		

		</div>
	</div>


</div>  




</main>

<script type="text/javascript">

function validate() {
	pattern= /^[0-9a-zA-Z]{4,7}$/;
	id = document.getElementById('postcode');
	idInvalidText = document.getElementById("invalid-text");

	postcode = id.value;
	postcode = postcode.trim();

	if(pattern.test(postcode) )
	{
		idInvalidText.innerText = "";
        window.location.replace("index.php/restaurants/index/"+postcode);
		return true;
	}
	else 
	{
		idInvalidText.innerText = "* Please enter a valid postcode";
		return false; 
	}
}


</script>

</body>

</html>
