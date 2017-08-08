<!DOCTYPE html>
<html>
  <head>
    <title>Home Page</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="styles.css" type="text/css" rel="stylesheet"/>
	<link href="resources/jquery-ui.css" rel="stylesheet">
  </head>
  <body>
	 <div class="rota" >

		<div id="header" >
			<p><span id="goto" >Goto</span><span id="rota" >Rota</span></p>
		</div>

        <div id="box" ></div>
       
		<div id="box_header" >
			<p><span>Login Page</span></p>
		</div>

		<form method="post" action="login_page.php">
		<div id="name_input_box" >
			<input id="name" name="name" type="text" value=""/>
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
		
		
	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			define('DB_NAME', 'rotas');
			define('DB_USER', 'root');
			define('DB_PASSWORD', '');
			define('DB_HOST', 'localhost');

		
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
		
		if (isset($_POST['submit'])){
			$name = mysql_entities_fix_string($conn, $_POST['name']);
			$pass = mysql_entities_fix_string($conn, $_POST['password']);
			
			if ($name == "" || $pass == "")
				echo "Please enter a value in both input boxes";
			
			else{
				
					$query = "SELECT lastName, employee_id FROM employee \n"
					. "WHERE lastName='$name' AND employee_id='$pass'";
					
					$result = $conn->query($query);
					if (!$result) die ("Database access failed: " . $conn->error);
				
					if ($result->num_rows == 0){
						echo "Invalid username/ password";
					}
					else{
						//die("you are now logged in. Please <a href='index.php'>" . 
						//"click here</a> to continue.");
						header("Location: index.php");
						exit;
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
    </div>
	
  </body>
</html>