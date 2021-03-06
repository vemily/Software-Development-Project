<html>
<?php
include('Conf/init.php');
include('Includes/header.html');

if (isset($_POST['submit'])) {
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
$datebefore = date('Y-m-d',strtotime('yesterday'));
// Retrieve current date and set as login date
$login_date = date("Y-m-d");
$result1 = mysqli_query($conn,"SELECT ChallengeID FROM challenge WHERE IssueDate = '$login_date'");
	if (!$result1) {	
	printf("Error: %s\n", mysqli_error($conn));
	exit();	
	}
$row1=mysqli_fetch_array($result1);
$chaID = $row1['ChallengeID'];
$username=mysqli_real_escape_string($conn,$_POST['username']);
$password=mysqli_real_escape_string($conn,$_POST['password']);


$sql="SELECT UserID FROM user WHERE Username='$username' ";
$pwdb = mysqli_query($conn,"SELECT UserID, Password, Identity FROM user WHERE Username='$username'");
$row = mysqli_fetch_array($pwdb);
// Check user identity
$identity = $row['Identity'];
$userID = $row['UserID'];

// Get user's last login date
$last_loginsql = mysqli_query($conn, "SELECT LastLogin, Coin, ConsecutiveLogin FROM user WHERE Username='$username'");
$last_login = mysqli_fetch_array($last_loginsql);
	if ($last_login['ConsecutiveLogin'] == 1){
		$reward = $last_login['Coin'] + 5;
	} elseif ($last_login['ConsecutiveLogin'] == 2) {
		$reward = $last_login['Coin'] + 10;
	} elseif ($last_login['ConsecutiveLogin'] == 3){
		$reward = $last_login['Coin'] + 15;
	} elseif ($last_login['ConsecutiveLogin'] >= 4){
		$reward = $last_login['Coin'] + 20;
	} 
$ConLogin = $last_login['ConsecutiveLogin'] + 1;
	

if ($result=mysqli_query($conn,$sql))
  {
  // Return the number of rows in result set
  $rowcount=mysqli_num_rows($result);
  }
//mysqli_close($conn);
if($rowcount==1) {
	// Decrypt password hash and verify from database
	if ($verifypassword = password_verify($password,$row['Password'])){
		// if last login not today
		if ($last_login['LastLogin'] < $login_date){
			$sql4 = "INSERT INTO challengestatus (ChallengeID,UserID) VALUES ('$chaID', '$userID')";			
			if(!mysqli_query($conn, $sql4))			
			{			
				die('Error:' .mysqli_error($conn));			
			}
		}
		if ($last_login['LastLogin'] == $login_date) {
			session_start();
			$_SESSION['Username']=$username;
			$_SESSION['userLevel']=$identity;
			$_SESSION['UID']=$row['UserID'];
			$_SESSION['ChallengeID']=$chaID;
			header('location: /sdp/index.php');
		} else {
		if ($last_login['ConsecutiveLogin'] == 0){
			session_start();
			$sql3 = "UPDATE user SET LastLogin='$login_date', ConsecutiveLogin='$ConLogin'，Coin='$reward' WHERE Username='$username'";
			if(!mysqli_query($conn,$sql3))
			{
				die('Error:' .mysqli_error($conn));
			}
			$_SESSION['Username']=$username;
			$_SESSION['userLevel']=$identity;
			$_SESSION['UID']=$row['UserID'];
			$_SESSION['ChallengeID']=$chaID;
			header('location: /sdp/index.php');
		} else{
			session_start();
			$sql2 = "UPDATE user SET LastLogin='$login_date', Coin='$reward', ConsecutiveLogin='$ConLogin' WHERE Username='$username'";
			if(!mysqli_query($conn,$sql2))
			{
				die('Error:' .mysqli_error($conn));
			}
			$_SESSION['Username']=$username;
			$_SESSION['userLevel']=$identity;
			$_SESSION['UID']=$row['UserID'];
			$_SESSION['ChallengeID']=$chaID;
			header('location: /sdp/index.php');
		}
	}}else{
	echo "<script>
	alert('Password incorrect. Please try again.');
	window.location.href='/sdp/login.php';
	</script>";
}}
elseif ($rowcount==null) {
echo "<script>
	alert('Username incorrect. Please try again.');
	window.location.href='/sdp/login.php';
	</script>";
}}}
?>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/body.css" />
    <link rel="stylesheet" type="text/css" href="CSS/login.css" />
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Cuprum|Raleway|Dosis|Titillium+Web|Oswald|Montserrat|Overpass+Mono|Roboto+Condensed|Saira+Extra+Condensed" rel="stylesheet">
</head>
<body>
<div class="op">
    <div class="login-bar">
        <div class="login-desc">
            <h2>Welcome back to <br/>CodeCube</h2>
            <h3>Don't own an account yet? <br/>Create an account in <a href="index.php">homepage</a> now.</h3>
        </div>
        <div class="login-form">
        <form action="" method="post" >
			<div class="login">
            <div class="login-head">
				<h1>Login</h1>
			</div>
            <div class="style-input">
				<i class="fa fa-user"></i>
				<input type="text" placeholder="Username" name="username" required />
			</div>
            <div class="style-input">
				<span class="fa fa-lock"></span>
				<input placeholder="Password" name="password" type="password" required="required"/>
			</div>
            <div class="style-input">
				<input type="submit" name="submit" value="Login"/></td>
				<a id="ady" href="#">Forget Password?</a>
			</div>
			</div>
        </form>
		</div>
	</div>
</div>
</body>
<?php 
include("Includes/footer.html");
?>
</html>