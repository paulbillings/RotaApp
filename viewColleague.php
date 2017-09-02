<!DOCTYPE html>
<html>
  <head>
    <title>GotoRota - View Colleague details</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="viewColStyle.css" type="text/css" rel="stylesheet"/>
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
	
	function getAllColleagues($section) {

		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
				
		$query = "SELECT * FROM employee \n"
			. "WHERE employee.section='$section'";
			
		$result = $conn->query($query);
		if (!$result) die ("Database access failed: " . $conn->error);

		$rows = $result->num_rows;
		
		if ($rows > 0) {
		echo 
			'<table id="rotaTable" border="2">
				<tr>
					<th name="fullname">Colleague name</th>
					<th name="number">Employee ID</th>
					<th name="section">Section</th>
					<th name="Contract Hours">Contract Hours</th>
				</tr>';

			$sorted = array();
			
			for ($j = 0 ; $j < $rows ; ++$j) {
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQLI_ASSOC);
			
				$fname = $row['firstName'];
				$sname = $row['lastName'];
				$fullname = $fname . ' ' . $sname;
				$sorted[1] = $fullname;
			
				$number = $row['employee_id'];
				$sorted[2] = $number;
				
				$colSection = $row['section'];
				$sorted[3] = $colSection;
				
				$hours = $row['contractHours'];
				$sorted[4] = $hours;
				
				echo
			'<tr>
				<td>'; echo $sorted[1]; echo '</td>
				<td>'; echo $sorted[2]; echo '</td>
				<td>'; echo $sorted[3]; echo '</td>
				<td>'; echo $sorted[4]; echo '</td>
			</tr>';
			
			}
			echo '</table>';
		
			$_SESSION['sectionChoose'] = $section;
			$_SESSION['startColView'] = false;
		}
		else {
			
				if (!$_SESSION['executedColView'] && !$_SESSION['startColView']){
					echo '<script language="javascript">';
					echo 'alert("No colleague records for selected section")';
					echo '</script>';
					$section = $_SESSION['sectionChoose'];
					$_SESSION['executedColView'] = true;
					getAllColleagues($section);
					
				}
				else if (!$_SESSION['startColView']) {
					echo '<script language="javascript">';
					echo 'alert("No colleague records for selected section")';
					echo '</script>';
					$section= $_SESSION['sectionChoose'];
					$_SESSION['executedColView'] = false;
					getAllColleagues($section);
					
				}
				else {
					$_SESSION['fail']= true;
					header("Location: login_page.php");
				}
		}		
			
		$result->close();
		$conn->close();	
	}
	
	if (isset($_SESSION['pass'])) {
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
		
		<form name="rotaForm" id="rotaForm" action="" method="post"> 
		
			<div id="sectionLabel" >
				<p><span>View colleague details from:</span></p> 
			</div>
			<div id="section" >
				<select id="section" name="section">
					<option value="Grocery"<?=$_SESSION['sectionChoose'] == "Grocery" ? ' selected="selected"' : ''?>>Grocery</option>
					<option value="Provisions"<?=$_SESSION['sectionChoose'] == "Provisions" ? ' selected="selected"' : ''?>>Provisions</option>
					<option value="Produce"<?=$_SESSION['sectionChoose'] == "Produce" ? ' selected="selected"' : ''?>>Produce</option>
					<option value="Checkouts"<?=$_SESSION['sectionChoose'] == "Checkouts" ? ' selected="selected"' : ''?>>Checkouts</option>
					<option value="Bakery"<?=$_SESSION['sectionChoose'] == "Bakery" ? ' selected="selected"' : ''?>>Bakery</option>
					<option value="File"<?=$_SESSION['sectionChoose'] == "File" ? ' selected="selected"' : ''?>>File</option>
					<option value="Management"<?=$_SESSION['sectionChoose'] == "Management" ? ' selected="selected"' : ''?>>Management</option>
				</select>
			</div>
			
			<input id="submitButton" type="submit" value="Submit" />
			
		</form>
		
      
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="index.js"></script>
  </body>
</html>
