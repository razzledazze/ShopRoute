<?php

// Create connection
$conn = new mysqli('localhost', 'root', 'password', 'shoproute');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


#BELOW - Makes sure the user has managed to login

$sql = "SELECT user_ID, userName, userPassword FROM users";
$result = $conn->query($sql);

$loginSuccess = "False";
$userID = "";

#BELOW provides me with variables for the username, userID, and loginsuccess boolean value
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$userArray = [
			"user_ID" => $row["user_ID"],
			"userName" => $row["userName"],
			"userPassword" => $row["userPassword"],
		];
		if ($userArray["userName"] == $_POST["userNameInput"]) {
			if ($userArray["userPassword"] == substr(sha1($_POST["userPasswordInput"]), 0, 10)) {
				$loginSuccess = "True";
				$userID = $userArray["user_ID"];
				$actualUserName = $userArray["userName"];
			}
		}
    }
}

#BELOW - Converts the data in the userCategories table into a list of category names

if ($loginSuccess == "True") {
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


	#BELOW fills the categorynames text file with the category names stored in the array
	$file = "categoryNames.txt";

	$lastEl = array_values(array_slice($categoryNames, -1))[0];

	file_put_contents($file, "");
	foreach ($categoryNames as $catName) {
		$current = file_get_contents($file);
		$catName .= PHP_EOL;
		$current .= $catName;
		file_put_contents($file, $current);	
	}

	#BELOW fills the username and ID text files with the variable values

	$file2 = "actualUserName.txt";
	file_put_contents($file2, $actualUserName);

	$file3 = "actualUserID.txt";
	file_put_contents($file3, $userID);

	#BELOW executes the python program
	$command = escapeshellcmd('createImage.py');
	$output = shell_exec($command);
	echo $output;

	header( 'Location: http://localhost/Actual Site/shoppingListPage.php' );
} else {
	session_start();
	$_SESSION['message'] = "Incorrect Login Details";
	header( 'Location: http://localhost/Actual Site/loginPage.php' );
}

?>