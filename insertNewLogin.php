<?php

$conn = new mysqli('localhost', 'root', 'password', 'shoproute');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

session_start();

$_SESSION['message'] = "Account Successfully Created";
$_SESSION['createAccountMessage'] = "Placeholder";

if ($_POST['firstNameInput'] != "" and $_POST['lastNameInput'] != "" and $_POST['userNameInput'] != "" and $_POST['userPasswordInput'] != "") {
	
	$userName = $_POST['userNameInput'];
	$userPassword = $_POST['userPasswordInput'];
	$firstName = $_POST['firstNameInput'];
	$lastName = $_POST['lastNameInput'];
	
	$userNameTaken = "False";
	
	$sql = "SELECT userName FROM users";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$singleName = $row['userName'];
			if ($userName == $singleName) {
				$userNameTaken = "True";
			}
		}
	} else {
		echo "0 results";
	}
	
	$userPassword = substr(sha1($userPassword), 0, 10);
	
	if ($userNameTaken == "False") {
		$sql = "INSERT INTO users (userName, userPassword, firstName, lastName) VALUES ('$userName','$userPassword','$firstName','$lastName')";
		if($conn->query($sql) === true){ 
			echo "Record was made successfully."; 
		} else{ 
			echo "ERROR: Could not able to execute $sql. ". $conn->error; 
		} 
	} else {
		$_SESSION['createAccountMessage'] = "That Username Is Already Taken";
	}
	
} else {
	$_SESSION['createAccountMessage'] = "Please Fill In All Fields";
}


#Below gives the new user a default item, ID=22 AKA bread
if ($_SESSION['createAccountMessage'] == "Placeholder"){
	
	#Finds the user's ID from their username
	$sql = "SELECT user_ID, userName FROM users";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
    // output data of each row
		while($row = $result->fetch_assoc()) {
			if ($row['userName'] == $userName) {
			$userID = $row["user_ID"];
			}
		}
	}
	#Actually inserts the list item for bread into usercategories
	$sql = "INSERT INTO usercategories (user_ID, category_ID) VALUES ('$userID',22)";
	if($conn->query($sql) === true){ 
		echo "Record was made successfully."; 
	} else{ 
		echo "ERROR: Could not able to execute $sql. ". $conn->error; 
	}
}


if ($_SESSION['createAccountMessage'] == "Placeholder"){
	header( 'Location: http://localhost/Actual Site/loginPage.php' );
} else {
	header( 'Location: http://localhost/Actual Site/createAccount.php' );
}
?>