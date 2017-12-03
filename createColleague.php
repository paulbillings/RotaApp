<!DOCTYPE html>
<html>
  <head>
    <title>GotoRota - Create Colleague details</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="createColStyle.css" type="text/css" rel="stylesheet"/>
	<link href="resources/jquery-ui.css" rel="stylesheet">
  </head>
  <body>
    <div class="rota" >
<?php
	session_start();
	if (!$_SESSION['logged_in']){
		header("Location: login_page.php");
	}
	if (!isset($_SESSION['sectionChoose'])){
		$_SESSION['sectionChoose'] = "Grocery";
	}

	define('DB_NAME', 'rotas');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) die($conn->connect_error);
	
	function mysql_entities_fix_string($conn, $string) {
		return htmlentities(mysql_fix_string($conn, $string));
	}

	function mysql_fix_string($conn, $string) {
		if (get_magic_quotes_gpc()) $string = stripcslashes($string);
		return $conn->real_escape_string($string);
	}
	
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		if (isset($_POST['submit'])) {
		
			
			$number = $_POST['form']['number'];
			
			if (empty($_POST['form']['fname'] && $_POST['form']['sname'] && $_POST['form']['number'] &&
				$_POST['form']['section'] && $_POST['form']['hours'] )) 
			{
				echo '<div style="display: none" id="dialog" title="Error">
							<p>Please enter all details</p>
						</div>';
				
			}
			else {
				
				$number = $_POST['form']['number'];
		
				if (!checkForExisting($number)) {
					$fname = $_POST['form']['fname'];
					$sname = $_POST['form']['sname'];
					$number = $_POST['form']['number'];
					$fSection = $_POST['form']['section'];
					$hours = $_POST['form']['hours'];
					
					insertColleague($number, $fname, $sname, $fSection, $hours);
				}
				
			}
		
	$conn->close();	
	}
}
	

	function getAllColleagues($section) {

		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
				
		
		echo 
			'<table id="rotaTable" border="2">
				<tr>
					<th name="firstname">First name</th>
					<th name="Surname">Surname</th>
					<th name="number">Employee ID</th>
					<th name="section">Section</th>
					<th name="Contract Hours">Contract Hours</th>
				</tr>';
			
			echo '<form name="createColForm" id="createColForm" action="" method="post">';
				
				echo
			'<tr>
				<td>'; echo '<input id="firstname" name="form[fname]" type="text" minlength="1" maxlength="15" value=""/>'; echo '</td>
				<td>'; echo '<input id="surname" name="form[sname]" type="text" minlength="1" maxlength="20" value=""/>'; echo '</td>
				<td>'; echo '<input id="number" name="form[number]" type="number" min="1" max="9999999999" step="1"  value=""/>'; echo '</td>
				<td>'; echo '<select id="formSection" name="form[section]">
								<option value="Grocery"'; $_SESSION['sectionChoose'] == "Grocery" ? ' selected="selected"' : ''; echo '>Grocery</option>
								<option value="Provisions"'; $_SESSION['sectionChoose'] == "Provisions" ? ' selected="selected"' : ''; echo '>Provisions</option>
								<option value="Produce"'; $_SESSION['sectionChoose'] == "Produce" ? ' selected="selected"' : ''; echo '>Produce</option>
								<option value="Checkouts"'; $_SESSION['sectionChoose'] == "Checkouts" ? ' selected="selected"' : ''; echo '>Checkouts</option>
								<option value="Bakery"'; $_SESSION['sectionChoose'] == "Bakery" ? ' selected="selected"' : ''; echo '>Bakery</option>
								<option value="File"'; $_SESSION['sectionChoose'] == "File" ? ' selected="selected"' : ''; echo '>File</option>
								<option value="Management"'; $_SESSION['sectionChoose'] == "Management" ? ' selected="selected"' : ''; echo '>Management</option>
							</select>
				<td>'; echo '<input id="hours" name="form[hours]" type="number" min="4" max="40" value="8"/>'; echo '</td>
			</tr>';
			
			
			
			echo '<tr><th colspan="15" >'; echo '<input class="colSubmit" type="submit" name="submit" value="Save Details"/>'; echo '</th></tr>';
			echo '</form>';	
			echo '</table>';
		
			$_SESSION['sectionChoose'] = $section;
			$_SESSION['startColView'] = false;
	
			
		//$result->close();
		//$conn->close();	
	}
	
	if (isset($_SESSION['user'])) {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
		
		if (!isset($_POST['section'])) {
			$section = $_SESSION['sectionChoose'];
		}
		else {
			$section = mysql_entities_fix_string($conn, $_POST['section']);
		}
		
		getAllColleagues($section);
		
		}
		
		
	function checkForExisting($number){

		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
			
		
			$query = "SELECT employee_id FROM employee \n"
			. "WHERE employee.employee_id=$number";

			$result = $conn->query($query);
			if (!$result) die ("Database access failed: " . $conn->error);
			
			$rows = $result->num_rows;
		
			if ($rows > 0) {
				echo '<div style="display: none" id="dialog" title="Error">
							<p>Colleague number already exists</p>
					</div>';
				return true;
			}
			else {
				return false;
			}
			
			$conn->close();	
		}	
		
		function insertColleague($number, $fname, $sname, $fSection, $hours){
			$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if ($conn->connect_error) die($conn->connect_error);
			
			$sql = "INSERT INTO employee (employee_id, firstName, lastName, section, contractHours) \n"
				. "VALUES ('$number', '$fname', '$sname', '$fSection', '$hours')";
				
				if ($conn->query($sql) === TRUE) {
					echo '<div style="display: none" id="dialog" title="Success">
							<p></p>
							<p>Colleague details successfully added.</p>
						</div>';
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
				
			$conn->close();	
		}

?>

		<div id="header" >
			<p><span id="goto" >Goto</span><span id="rota" >Rota</span></p>
		</div>
		
		<nav>
			<ul>
				<li><a href="userProfile.php">Home</a></li>
				<li><a href="adminMenu.php" class="current">Admin</a></li>
				<li><a href="">Help</a></li>
				<li><a href="login_page.php">Logout</a></li>
			</ul>
		</nav>
		
		<div id="intro">
			<p>Please enter new colleague details below, then press "Save Details"</p>
		</div>
		
      
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="index.js"></script>
  </body>
</html>
