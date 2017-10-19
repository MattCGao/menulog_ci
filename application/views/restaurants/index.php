<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	//1. define html head
	$this->load->view('common/head');
?>



<body onload="showRestaurantsList();">

<?php $this->load->view('common/nav');?>

<div class="container-fluid" id="result">

<div id="txtHint">
<b>Restaurants in <span style="color:red"> <?php echo $postcode;?> </span>infomation will be listed here .....</b>
</div>

</div>
<script>

function showRestaurantsList(){

	str = '<?php echo $postcode;?>';

    if(window.XMLHttpRequest) {

    	// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    
    } else {
        
    	// code for IE6, IE5
        xmlhttp = new ActiveXObject("Mircrosoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange = function() {
    
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        
        	document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
        }
    };

    str = "getRestaurantList/"+str;

    xmlhttp.open("GET", str, true);
    xmlhttp.send();
	
}

</script>

</body>
</html>
