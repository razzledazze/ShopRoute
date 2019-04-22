<?php
$conn = new mysqli('localhost', 'root', 'password', 'shoproute');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sqlA = "SELECT user_ID, category_ID FROM userCategories";
$sqlB = "SELECT category_ID, categoryName FROM categories";

$resultA = $conn->query($sqlA);
$resultB = $conn->query($sqlB);

$categoryIDs = [];
foreach ($conn->query($sqlA) as $rowA) {
	if ($rowA['user_ID'] == $userID) {
		array_push($categoryIDs, $rowA["category_ID"]);
	}
}

$categoryNames = [];
foreach ($categoryIDs as $catID) {
	foreach ($conn->query($sqlB) as $rowB) {
		if ($rowB["category_ID"] == $catID) {
			array_push($categoryNames, $rowB["categoryName"]);
		}
	}
}

$conn->close();


$file = "categoryNames.txt";

$lastEl = array_values(array_slice($array, -1))[0];

file_put_contents($file, "");
foreach ($categoryNames as $catName) {
	$current = file_get_contents($file);
	if ($catName != $lastEl) {
		$catName .= PHP_EOL;
		$current .= $catName;
		file_put_contents($file, $current);
	}	
}

$file2 = "actualUserName.txt";
file_put_contents($file2, $actualUserName);

$file3 = "actualUserID.txt";
file_put_contents($file3, $userID);

header( 'Location: http://localhost/Actual Site/ShoppingListPage.php' );
?>