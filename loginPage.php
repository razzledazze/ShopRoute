<html>
<body>

<link rel="stylesheet" type="text/css" href="cleanList.css">

<h1>Shop Route</h1>

<?php 
$message = ""; 
session_start();
if (isset($_SESSION['message'])) {
	$message = $_SESSION['message'];
}

if ($message == "") {
	$message = "Log In";
}
session_destroy();
?>

<div class="message"><?php echo $message ?></div>

<form action="http://localhost/Actual Site/loginCheck.php" method="post">
	<div class="centredDiv" id="loginInputs">
		<div class="inLabel" id="inputLabel">Username</div>
		<input type="text" id="userNameInput" name="userNameInput">
		<div class="inLabel" id="inputLabel">Password</div>
		<input type="password" id="userPasswordInput" name="userPasswordInput">
		<hr>
		<input type="submit" id="logIn" value="LOGIN" />
	</div>
</form>

<form action="http://localhost/Actual Site/createAccount.php">
	<div class="centredDiv" id="loginInputs">
		<div class="smallText" id="noAcccount?">Don't Have An Account?</div>
		<input type="submit" id="createAccount" value="Create An Account">
	</div>
<form>

<div class="centredDiv">
<button type="button" onclick="showWeatherInfo()">Show Weather Information</button>
</div>

<?php
#$user_ip = $_SERVER['REMOTE_ADDR'];
#$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
#$city = $geo["geoplugin_city"];

$srcPartOne = "http://api.openweathermap.org/data/2.5/weather?q=";
$srcLocation = 'Macclesfield';
$srcPartTwo = "&units=metric&appid=d42b573ae6cb8e35b38742fd2b4688e5";

$jsonfile = file_get_contents($srcPartOne.$srcLocation.$srcPartTwo);
$jsondata = json_decode($jsonfile);
$temp = $jsondata->main->temp;
$desc = $jsondata->weather[0]->description;
$mintemp = $jsondata->main->temp_min;
$maxtemp = $jsondata->main->temp_max;

echo '<div class="message" id="weatherInfo">The weather in ';
echo $srcLocation;
echo ' is supposed to be ';
echo $desc;
echo ' today';
echo '<div class="message" id="weatherInfo">Highs of ';
echo $maxtemp;
echo '°C, and lows of ';
echo $mintemp;
echo '°C</div>'
?>

<script>
var weatherInfo = document.getElementById('weatherInfo');
weatherInfo.style.display = "none";

function showWeatherInfo() {
	weatherInfo.style.display = "block";
}
</script>

</body>
</html>