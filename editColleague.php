<!DOCTYPE html>
<html>
  <head>
    <title>GotoRota - Create Colleague details</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="editColStyle.css" type="text/css" rel="stylesheet"/>
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
			
			if (isset($_POST['section'])){
				$section = mysql_entities_fix_string($conn, $_POST['section']);
				$_SESSION['sectionChoose'] = $section;
			}
			
			$rowsInputted = $_SESSION['colRows'];
			
			for ($a = 0 ; $a < $rowsInputted ; ++$a) {
		
				$number = $_POST['form'][$a]['number'];
			
				if (empty($_POST['form'][$a]['fname'] && $_POST['form'][$a]['sname'] && $_POST['form'][$a]['number'] &&
					$_POST['form'][$a]['section'] && $_POST['form'][$a]['hours'] )) 
				{
					echo ('Please enter all details');
				}
				else {
				
					$number = $_POST['form'][$a]['number'];
		
					checkForExisting($number);
					
					$fname = $_POST['form'][$a]['fname'];
					$sname = $_POST['form'][$a]['sname'];
					$number = $_POST['form'][$a]['number'];
					$fSection = $_POST['form'][$a]['section'];
					$hours = $_POST['form'][$a]['hours'];
					print_r ($fname);
					print_r ($sname);
					print_r ($number);
					print_r ($fSection);
					print_r ($hours);
					changeColleague($number, $fname, $sname, $fSection, $hours);
					
				}
			}
	$conn->close();	
	}
}
	
	
	function getAllColleagues($section) {

		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
				
		$query = "SELECT * FROM employee \n"
			. "WHERE employee.section='$section'";
			
		$result = $conn->query($query);
		if (!$result) die ("Database access failed: " . $conn->error);

		$rows = $result->num_rows;
		$_SESSION['colRows'] = $rows;
		
		if ($rows > 0) {
		
		echo 
			'<table id="rotaTable" border="2">
				<tr>
					<th name="firstname">First name</th>
					<th name="Surname">Surname</th>
					<th name="number">Employee ID</th>
					<th name="section">Section</th>
					<th name="Contract Hours">Contract Hours</th>
				</tr>';
				
			$sorted = array();
			
			for ($j = 0 ; $j < $rows ; ++$j) {
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQLI_ASSOC);
			
				$fname = $row['firstName'];
				$sorted[1] = $fname;
				
				$sname = $row['lastName'];
				$sorted[2] = $sname;
				
				$number = $row['employee_id'];
				$sorted[3] = $number;
				
				$colSection = $row['section'];
				$sorted[4] = $colSection;
				
				$hours = $row['contractHours'];
				$sorted[5] = $hours;	
			
			echo '<form name="createColForm" id="createColForm" action="" method="post">';
				
				echo
			'<tr>
				<td>'; echo '<input id="firstname" name="form['; echo $j; echo '][fname]" type="text" minlength="1" maxlength="15" value="' . $sorted[1]; echo '"/></td>
				<td>'; echo '<input id="surname" name="form['; echo $j; echo '][sname]" type="text" minlength="1" maxlength="20" value="' . $sorted[2]; echo '"/></td>
				<td>'; echo '<input id="number" name="form['; echo $j; echo '][number]" type="number" min="1" max="9999999999" step="1"  value="' . $sorted[3]; echo '"/></td>
				<td>'; echo '<select id="formSection" name="form['; echo $j; echo '][section]" >
								<option value="Grocery"'; if ($sorted[4] == "Grocery") { echo ' selected="selected"'; }  echo '>Grocery</option>
								<option value="Provisions"'; if ($sorted[4] == "Provisions") { echo ' selected="selected"'; }  echo '>Provisions</option>
								<option value="Produce"'; if ($sorted[4] == "Produce") { echo ' selected="selected"'; } echo '>Produce</option>
								<option value="Checkouts"'; if ($sorted[4] == "Checkouts") { echo ' selected="selected"'; } echo '>Checkouts</option>
								<option value="Bakery"'; if ($sorted[4] == "Bakery") { echo ' selected="selected"'; } echo '>Bakery</option>
								<option value="File"'; if ($sorted[4] == "File") { echo ' selected="selected"'; } echo '>File</option>
								<option value="Management"'; if ($sorted[4] == "Management") { echo ' selected="selected"'; } echo '>Management</option>
							</select>
				<td>'; echo '<input id="hours" name="form['; echo $j; echo '][hours]" type="number" min="4" max="40" value="' . $sorted[5]; echo '"/></td>
			</tr>';
			
			}
			
			echo '<tr><th colspan="15" >'; echo '<input class="colSubmit" type="submit" name="submit" value="Save Details"/>'; echo '</th></tr>';
			echo '</form>';	
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
				$delete = "DELETE FROM employee WHERE employee.employee_id = $number";
	
				if ($conn->query($delete) === TRUE) {
					echo "records deleted successfully";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
			
			
			$conn->close();	
		}	
		
		function changeColleague($number, $fname, $sname, $fSection, $hours){
			$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if ($conn->connect_error) die($conn->connect_error);
			
			$sql = "INSERT INTO employee (employee_id, firstName, lastName, section, contractHours) \n"
				. "VALUES ('$number', '$fname', '$sname', '$fSection', '$hours')";
				
				if ($conn->query($sql) === TRUE) {
					echo "New record created successfully";
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
		
		<form name="rotaForm" id="rotaForm" action="" onsubmit="" method="post"> 
		
			<div id="sectionLabel" >
				<p><span>View all colleague details from:</span></p> 
			</div>
			<div id="sectionDiv" >
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
			
			<input id="submitButton" type="submit" name="sectionSubmit" value="Submit" />
		</form>
		
      
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="index.js"></script>
  </body>
</html>