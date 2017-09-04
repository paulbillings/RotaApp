<!DOCTYPE html>
<html>
  <head>
    <title>GotoRota - Delete Rota Page</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="adminStyle.css" type="text/css" rel="stylesheet"/>
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
	
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	
	if (isset($_POST['form'])) {
		$weekEnding = $_SESSION['weekEnding'];
		
		$SelectedElem = implode(",", array_keys($_POST['form']));
		print_r ($SelectedElem);
	
		$number = $SelectedElem;
		
		$delete = "DELETE FROM schedule WHERE schedule.employee_id = $number \n"
					. "AND schedule.Week_ending = '$weekEnding'";
	
		if ($conn->query($delete) === TRUE) {
			echo '<div id="dialog" title="Success">
							<p>Rota details successfully Deleted.</p>
						</div>';
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


function getAllRotas($weekEnding, $section) {

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) die($conn->connect_error);
	
		$week_ending = $weekEnding;
		$week_beginning = date('Y-m-d', strtotime('-6 day', strtotime($week_ending)));
		
	$query = "SELECT employee.employee_id FROM employee,schedule,date \n"
		. "WHERE schedule.employee_id=employee.employee_id AND schedule.Week_ending='$week_ending'\n"
		. "AND date.fulldate=schedule.fulldate\n"
		. "AND date.fulldate BETWEEN '$week_beginning' AND '$week_ending'\n"
		. "AND employee.section='$section'";
		

	$result = $conn->query($query);
	if (!$result) die ("Database access failed: " . $conn->error);

		$rows = $result->num_rows;
		
		if ($rows > 0) {
		echo 
			'<table id="rotaTable" border="2">
				<tr>
					<th></th>
					<th name="Sunday" colspan="2">Sunday</th>
					<th name="Monday" colspan="2">Monday</th>
					<th name="Tuesday" colspan="2">Tuesday</th>
					<th name="Wednesday" colspan="2">Wednesday</th>
					<th name="Thursday" colspan="2">Thursday</th>
					<th name="Friday" colspan="2">Friday</th>
					<th name="Saturday" colspan="2">Saturday</th>
					<th></th>
				</tr>
				<tr>
					<th></th>
					<th>Start</th>
					<th>Finish</th>
					<th>Start</th>
					<th>Finish</th>
					<th>Start</th>
					<th>Finish</th>
					<th>Start</th>
					<th>Finish</th>
					<th>Start</th>
					<th>Finish</th>
					<th>Start</th>
					<th>Finish</th>
					<th>Start</th>
					<th>Finish</th>
					<th></th>
				</tr>';

		$sorted = array();
		$checkName = array();
		$big = 0;

	for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$number = $row['employee_id'];
		//echo $number;
		if (!in_array($row['employee_id'], $checkName)) {
		
			$queryI = "SELECT firstname,lastname,start_shift,end_shift,day FROM employee,schedule,date \n"
			. "WHERE schedule.employee_id=employee.employee_id AND schedule.Week_ending='$week_ending'\n"
			. "AND date.fulldate=schedule.fulldate\n"
			. "AND date.fulldate BETWEEN '$week_beginning' AND '$week_ending'\n"
			. "AND employee.employee_id='$number'";

			$resultI = $conn->query($queryI);
			if (!$resultI) die ("Database access failed: " . $conn->error);

			$sorted[0] = 'Day';
			$sorted[1] = 'Off';
			$sorted[2] = 'Day';
			$sorted[3] = 'Off';
			$sorted[4] = 'Day';
			$sorted[5] = 'Off';
			$sorted[6] = 'Day';
			$sorted[7] = 'Off'; 
			$sorted[8] = 'Day'; 
			$sorted[9] = 'Off';
			$sorted[10] = 'Day'; 
			$sorted[11] = 'Off'; 
			$sorted[12] = 'Day';
			$sorted[13] = 'Off'; 
			$sorted[14] = 'Name';	
			
			$rowsI = $resultI->num_rows;
			
			for ($b = 0 ; $b < $rowsI ; ++$b) {
				$resultI->data_seek($b);
				$rowI = $resultI->fetch_array(MYSQLI_ASSOC);
			
	
				if ($rowI['day'] === 'Sunday') {
					$sorted[0] = $rowI['start_shift'];
					$sorted[1] = $rowI['end_shift'];
				}
				if ($rowI['day'] === 'Monday') {
					$sorted[2] = $rowI['start_shift'];
					$sorted[3] = $rowI['end_shift'];
				}
				if ($rowI['day'] === 'Tuesday') {
					$sorted[4] = $rowI['start_shift'];
					$sorted[5] = $rowI['end_shift'];
				}
				if ($rowI['day'] === 'Wednesday') {
					$sorted[6] = $rowI['start_shift'];
					$sorted[7] = $rowI['end_shift'];
				}
				if ($rowI['day'] === 'Thursday') {
					$sorted[8] = $rowI['start_shift'];
					$sorted[9] = $rowI['end_shift'];
				}
				if ($rowI['day'] === 'Friday') {
					$sorted[10] = $rowI['start_shift'];
					$sorted[11] = $rowI['end_shift'];
				}
				if ($rowI['day'] === 'Saturday') {
					$sorted[12] = $rowI['start_shift'];
					$sorted[13] = $rowI['end_shift'];
				}
			}
		
			$fname = $rowI['firstname'];
			$sname = $rowI['lastname'];
			$fullname = $fname . ' ' . $sname;
			$sorted[14] = $fullname;
			
			echo '<form name="deleteForm" id="deleteForm" action="" method="post">';
					
			echo
			'<tr>
				<th>'; echo $sorted[14]; echo '</th>
				<td>'; echo $sorted[0]; echo '</td>
				<td>'; echo $sorted[1]; echo '</td>
				<td>'; echo $sorted[2]; echo '</td>
				<td>'; echo $sorted[3]; echo '</td>
				<td>'; echo $sorted[4]; echo '</td>
				<td>'; echo $sorted[5]; echo '</td>
				<td>'; echo $sorted[6]; echo '</td>
				<td>'; echo $sorted[7]; echo '</td>
				<td>'; echo $sorted[8]; echo '</td>
				<td>'; echo $sorted[9]; echo '</td>
				<td>'; echo $sorted[10]; echo '</td>
				<td>'; echo $sorted[11]; echo '</td>
				<td>'; echo $sorted[12]; echo '</td>
				<td>'; echo $sorted[13]; echo '</td>
				<th>';echo '<input class="delete" type="submit" name="form['; echo $number; echo ']" value="Delete"/>'; echo '</th>
			</tr>';
			$big++;
			array_push($checkName, $number);
		}
		
	}
	echo '</form>';	
	echo '</table>';
	
	
	$convertWeek = date("M jS, Y", strtotime($week_ending));
	
	echo '<div id="weekLabel">';
	echo '<p>Week Ending: </p>'; 
	echo '<div id="week">';
	echo $convertWeek;
	echo '</div>';
	echo '</div>';
			
			$_SESSION['weekEnding'] = $week_ending;
			$_SESSION['sectionChoose'] = $section;
			$_SESSION['startDelete'] = false;
			
		}
		else {
			
				if (!$_SESSION['executeDelete'] && !$_SESSION['startDelete']){
					echo '<div id="dialog" title="Error">
							<p>No rotas for selected week/section</p>
						</div>';
					$weekEnding = $_SESSION['weekEnding'];
					$section = $_SESSION['sectionChoose']; 
					getAllRotas($weekEnding, $section);
					$_SESSION['executeDelete'] = true;
				}
				else if (!$_SESSION['startDelete']) {
					echo '<div id="dialog" title="Error">
							<p>No rotas for selected week/section</p>
						</div>';
					$weekEnding = $_SESSION['weekEnding'];
					$section= $_SESSION['sectionChoose'];
					getAllRotas($weekEnding, $section);
					$_SESSION['executeDelete'] = false;
				}
				else {
					//$_SESSION['fail']= true;
					header("Location: adminMenu.php");
				}
		}		
		
	$result->close();
	$conn->close();	
	
}


		if (isset($_SESSION['user'])) {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
		
		
		if (!isset($_POST['week_ending'])) {
			$weekEnding = date('Y-m-d',strtotime('next saturday'));
			$section = $_SESSION['sectionChoose'];
		}
		else {
			$weekEnding = mysql_entities_fix_string($conn, $_POST['week_ending']);
			$section = mysql_entities_fix_string($conn, $_POST['section']);	
		}
		
		getAllRotas($weekEnding, $section); 
		
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
				<p><span>View all rotas for which section:</span></p> 
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
			
			<div id="rotaLabel" >
				<p><span>View all rotas for week ending:</span></p> 
			</div>
			<div id="dateChoice" >
				<input id="week_ending" class="week_ending" type="text" name="week_ending" maxlength="10" required="true" placeholder="yyyy-mm-dd"  />
			</div>
			<input id="submitButton" type="submit" value="Submit" />
		</form>
	  	

      
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="delete.js"></script>
  </body>
</html>
