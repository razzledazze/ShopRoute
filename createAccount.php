
<html>
<body>

<link rel="stylesheet" type="text/css" href="cleanList.css">

<form id="logOutForm" action="loginPage.php">
<input id="logout" type="submit" class="tranButton" value="BACK" />
</form>

<h1>Create An Account</h1>

<form action="insertNewLogin.php" method="post">

<div class="centredDiv" id="loginInputs">
	<div class="inLabel" id="inputLabel">First Name</div>
	<input type="text" id="firstNameInput" name="firstNameInput">
	<div class="inLabel" id="inputLabel">Last Name</div>
	<input type="text" id="lastNameInput" name="lastNameInput">
	<div class="inLabel" id="inputLabel">Choose A Username</div>
	<input type="text" id="userNameInput" name="userNameInput">
	<div class="inLabel" id="inputLabel">Choose A Password</div>
	<input type="password" id="userPasswordInput" name="userPasswordInput">
	<hr>
	<input type="submit" id="createAccount" value="Create Account" />
</div>
</form>
	
<?php 
$message = ""; 
session_start();
if (isset($_SESSION['createAccountMessage'])) {
	$message = $_SESSION['createAccountMessage'];
}

echo '<div class="centredDiv"><div class="inLabel">';
echo $message;
echo '</div></div>';

?>