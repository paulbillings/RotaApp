<?php
		session_start();
		
		 if(isset($_SESSION['fail'])){
			echo '<script language="javascript">';
			echo 'alert("Sorry no rota details")';
			echo '</script>';
		 }
		
		session_destroy();
		session_start();
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			define('DB_NAME', 'rotas');
			define('DB_USER', 'root');
			define('DB_PASSWORD', '');
			define('DB_HOST', 'localhost');

		
			$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if ($conn->connect_error) die($conn->connect_error);
		
		
		
			if (isset($_POST['submit'])){
				$number = mysql_entities_fix_string($conn, $_POST['colNumber']);
				$_SESSION['user'] = $number;
				$pass = mysql_entities_fix_string($conn, $_POST['password']);
				
				if ($number == "" || $pass == ""){
					echo "<script type='text/javascript'>alert('Please input both username and password');</script>";
				}
				else {
						$query = "SELECT password, employee_id FROM employee \n"
						. "WHERE employee_id='$number'";
						
						$result = $conn->query($query);
						if (!$result) die ("Database access failed: " . $conn->error);
						
						$result->data_seek(0);
						$row = $result->fetch_array(MYSQLI_ASSOC);
					
						if ($result->num_rows == 0){
							echo "<script type='text/javascript'>alert('Invalid username/ password');</script>";
						}
						
						if ($result->num_rows == 1){
							if ($row['password'] == "password"){
								header("Location: register.php");
							}
							else {
								$hashedPassword = $row['password'];
								if (password_verify($pass, $hashedPassword)){
									$_SESSION['user'] = $number;
									$_SESSION['pass'] = $pass;
									$_SESSION['executed'] = false;
									$_SESSION['executedAdmin'] = false;
									$_SESSION['executedEdit'] = false;
									$_SESSION['executeDelete'] = false;
									$_SESSION['logged_in'] = true;
									$_SESSION['start'] = true;
									$_SESSION['startAdmin'] = true;
									$_SESSION['startCreate'] = true;
									$_SESSION['startEdit'] = true;
									$_SESSION['startDelete'] = true;
									$_SESSION['executedColView'] = false;
									$_SESSION['startColView'] = true;
									$_SESSION['nextPrev'] = false;
									header("Location: userProfile.php");
									exit;
								}
								else{
									echo "<script type='text/javascript'>alert('Invalid username/ password');</script>";
								}
							
							}
						}
						
							
						
						
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
    <link href="loginPageStyle.css" type="text/css" rel="stylesheet"/>
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
			<p><span>Login Page</span></p>
		</div>

		<form method="post" action="login_page.php">
		<div id="name_input_box" >
			<input id="colNumber" name="colNumber" type="text" value=""/>
		</div>

		<div id="password_input_box" >
			<input id="password" name="password" type="password" value="" maxlength="30"/>
		</div>

		<div id="username_label" class="text">
			<p><span>Username:</span></p>
		</div>

		<div id="password_label" class="text">
			<p><span>Password:</span></p>  
		</div>

		<div id="submit_box" >
			<input id="submit" name="submit" type="submit" value="Submit"/>
		</div>
		</form>
		
		
	
    </div>
	
  </body>
</html>