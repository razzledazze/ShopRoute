
<html>

<body>

<link rel="stylesheet" type="text/css" href="cleanList.css">

<form id="logOutForm" action="loginPage.php">
<input id="logout" type="submit" class="tranButton" value="LOGOUT" />
</form>

<?php

#Below fetches the user's name from the text file (written to from loginCheck.php) and displays it as a header

echo '<h1>';
$file_handle = fopen("actualUserName.txt", "r");
while (!feof($file_handle)) {
	$line = fgets($file_handle);
	echo strtoupper($line);
}
echo "'s SHOPPING LIST";
echo '</h1>';
?>


<form id="containItems" action="saveList.php" method="post" class="centredDiv">
<div class="noMargin" id="listDiv">

<button type="button" onclick="addItem()">Add Another Item</button>

<?php
		
#Below fetches all records from usercategories table where the user_ID is the current user, and displays each category item as a select with the default value as the category
		
$tempID = 100;

$file_handle = fopen("categoryNames.txt", "r");
while (!feof($file_handle)) {
   $line = fgets($file_handle);
   if ($line != "") {
	   echo '<div id="listItem'.$tempID.'"'.'>';
	   
	   echo '<select id="listSelect" name="cat1[]">';
	   echo '<option id="startValue" value="'.$line.'" selected>';
	   echo $line;
	   echo '</option>';
	   echo '<option value="beverages">'.'Beverages'.'</option>';
	   echo '<option value="bread">'.'Bread'.'</option>';
	   echo '<option value="canned">'.'Canned'.'</option>';
	   echo '<option value="dairy">'.'Dairy'.'</option>';
	   echo '<option value="dry">'.'Dry'.'</option>';
	   echo '<option value="frozen">'.'Frozen'.'</option>';
	   echo '<option value="meat">'.'Meat'.'</option>';
	   echo '<option value="fruitveg">'.'Fruit/Veg'.'</option>';
	   echo '<option value="cleaners">'.'Cleaners'.'</option>';
	   echo '<option value="papergoods">'.'Paper Goods'.'</option>';
	   echo '<option value="personalcare">'.'Personal Care'.'</option>';
	   echo '<option value="baby">'.'Baby'.'</option>';
	   echo '<option value="pet">'.'Pet'.'</option>';
	   echo '<option value="greetingscards">'.'Greetings Cards'.'</option>';
	   echo '<option value="electronics">'.'Electronics'.'</option>';
	   echo '<option value="alcohol">'.'Alcohol'.'</option>';
	   echo '<option value="confectionery">'.'Confectionery'.'</option>';
	   echo '<option value="freshbakery">'.'Fresh Bakery'.'</option>';
	   echo '<option value="freshconfectionery">'.'Fresh Confectionery'.'</option>';
	   echo '<option value="delicounter">'.'Deli Counter'.'</option>';
	   echo '<option value="continental">'.'Continental'.'</option>';
	   echo '</select>';
	   
	   echo '<button id="button'.$tempID.'" onclick="removeItem(this.id)" type="button"> REMOVE ITEM</button>';
	   
	   $tempID += 1;
	   
	   echo '<div class="blueLine">';
	   echo '</div>';
	   
	   echo '</div>';
   }
}	
?>
</div>

<input type="submit" class="button" name="test" id="test" value="Save List + Calculate Route" />
</form>

<div class="outImage" id="outImage">
<img src="outputImage.svg">
</div>

<script>

var nextItemId = <?php echo $tempID ?>;

function removeItem(clicked_id) {
	var toRemove = clicked_id.slice(6,9);
	if (toRemove > 100) {
		var listItem = document.getElementById("listItem" + toRemove);
		listItem.parentNode.removeChild(listItem);
	}
}

function addItem() {
	lastItemId = nextItemId - 1;
	var form = document.getElementById('listItem100');
	clone = form.cloneNode(true);
	clone.id = 'listItem' + nextItemId;
	clone.getElementsByTagName('button')[0].id = 'button' + nextItemId;

	++nextItemId;
	
	var con = document.getElementById('listDiv');
	con.appendChild(clone);
}
</script>

</body>

</html>