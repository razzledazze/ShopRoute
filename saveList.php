<?php

$conn = new mysqli('localhost', 'root', 'password', 'shoproute');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$sql = 'SELECT category_ID, categoryName FROM categories';
$result = $conn->query($sql);

$categoryIDs = [];

$array1 = [];
foreach ($_POST['cat1'] as $item) {
	array_push($array1, trim($item));
}

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		foreach ($array1 as $catName) {
			$categoryArray = [
				"category_ID" => $row["category_ID"],
				"categoryName" => $row["categoryName"],
			];
			if ($categoryArray["categoryName"] == $catName) {
				array_push($categoryIDs, $categoryArray["category_ID"]);
			}
		}
	}
}




$file_handle = fopen("actualUserID.txt", "r");
	while (!feof($file_handle)) {
		$line = fgets($file_handle);
		$user_ID = $line;
	}
	
$sql = 'DELETE FROM usercategories WHERE user_ID='.$user_ID;

if($conn->query($sql) === true){ 
    echo "Record was deleted successfully."; 
} else{ 
    echo "ERROR: Could not able to execute $sql. ". $conn->error; 
} 



foreach ($categoryIDs as $catID) {
	$sql = 'INSERT INTO usercategories (user_ID, category_ID) VALUES ('.$user_ID.', '.$catID.')';
	if($conn->query($sql) === true){ 
		echo "Record was made successfully."; 
	} else{ 
		echo "ERROR: Could not able to execute $sql. ". $conn->error; 
	} 
}


session_start();
$_SESSION['message'] = "Please log in again to see your updated list";

header( 'Location: http://localhost/Actual Site/loginPage.php' );
?>