<?php
		session_start();
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			define('DB_NAME', 'rotas');
			define('DB_USER', 'root');
			define('DB_PASSWORD', '');
			define('DB_HOST', 'localhost');

		
			$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if ($conn->connect_error) die($conn->connect_error);
	
			
				if (isset($_POST['submitReset'])){
						$number = $_SESSION['user'];
						echo $number;
						$newPass = mysql_entities_fix_string($conn, $_POST['passwordReset']);
						echo $newPass;
						$hashedpassword = password_hash($newPass, PASSWORD_DEFAULT);
						echo $hashedpassword;
						
						$sql = "UPDATE employee \n"
						. "SET password = '$hashedpassword' \n"
						. "WHERE employee.employee_id='$number'";
						
						if ($conn->query($sql) === TRUE) {
							echo '<div style="display: none" id="dialog" title="Success">
							<p>Password updated successfully</p>
							<p>You can now login with your new password</p>
							</div>';
							header("Location: login_page.php");
						} else {
							echo "Error: " . $sql . "<br>" . $conn->error;
						}
						
					}
		
		}	
		
	function mysql_entities_fix_string($conn, $string) {
	return htmlentities(mysql_fix_string($conn, $string));
	}

	function mysql_fix_string($conn, $string) {
	if (get_magic_quotes_gpc()) $string = stripcslashes($string);
	return $conn->real_escape_string($string);
	}	
		
		
	?>
<!DOCTYPE html>	
<html>
  <head>
    <title>GotoRota - Login Page</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="registerStyle.css" type="text/css" rel="stylesheet"/>
	<link href="resources/jquery-ui.css" rel="stylesheet">
  </head>
  <body>
	 <div class="rota" >

		<div id="header" >
			<p><span id="goto" >Goto</span><span id="rota" >Rota</span></p>
		</div>
		
		<div id="by" >
			<p><span id="create" >Created by Paul Billings</span>
		</div>

        <div id="box" ></div>
       
		<div id="box_header" >
			<p><span>Register new password</span></p>
		</div>

		<form method="post" action="">
		<div id="passwordResetBox" >
				<p id="intro">As you are a new user can you input a new password please</p>
				<p id="password_label">New password:</p>
				<div id="password_input_box">
					<input id="passwordReset" name="passwordReset" type="password" value="" maxlength="30"/>
				</div>
				<div id="submit_box">
					<input id="submitReset" name="submitReset" type="submit" value="Submit"/>
				</div>
				
		</div>
		</form>
		
		
	
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="index.js"></script>
  </body>
</html>