<!DOCTYPE html>
<html>
  <head>
    <title>GotoRota - Edit Rota Page</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="editRotaStyle.css" type="text/css" rel="stylesheet"/>
	<link href="resources/jquery-ui.css" rel="stylesheet">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
  </head>
 <body>
    <div class="rota" > 
<?php
	session_start();
	if (!$_SESSION['logged_in']){
		header("Location: login_page.php");
	}
	if (!isset($_SESSION['sectionChoose']) && $_SESSION['startCreate']){
		$_SESSION['sectionChoose'] = "Grocery";
	}

	define('DB_NAME', 'rotas');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) die($conn->connect_error);
	
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if (isset($_POST['sectionSubmit'])) {
		$weekEnding = mysql_entities_fix_string($conn, $_POST['selectWeek']);
	
		if (isset($_POST['section'])){
			$section = mysql_entities_fix_string($conn, $_POST['section']);
			$_SESSION['sectionChoose'] = $section;
		}
		
		//getAllRotas($weekEnding, $section);
	}
	
	if (isset($_POST['submit'])) {
		
		$weekEnding = mysql_entities_fix_string($conn, $_POST['week_ending']);
		
		
		if (isset($_POST['section'])){
			$section = mysql_entities_fix_string($conn, $_POST['section']);
			$_SESSION['sectionChoose'] = $section;
		}
		
		$rowsInputted = $_SESSION['rows'];
		
		for ($a = 0 ; $a < $rowsInputted ; ++$a) {
			$week = $weekEnding;
			$number = $_POST['form'][$a]['number'];
			
			if (empty($_POST['form'][$a]['sunStart'] || $_POST['form'][$a]['monStart'] || $_POST['form'][$a]['tueStart'] ||
				$_POST['form'][$a]['wedStart'] || $_POST['form'][$a]['thuStart'] || $_POST['form'][$a]['friStart'] || 
				$_POST['form'][$a]['satStart'])) 
			{
				echo ('No shifts entered');
			}
			else {
		
				checkForExisting($week, $number);
			
				if (!empty($_POST['form'][$a]['sunStart'] && $_POST['form'][$a]['sunFinish'])){
					$dayDate = date('Y-m-d',strtotime($weekEnding .' -6 day'));
					$start = $_POST['form'][$a]['sunStart'];
					$finish = $_POST['form'][$a]['sunFinish'];
					print_r ($week);
					print_r ($number);
					print_r ($dayDate);
					print_r ($start);
					print_r ($finish);
					insertShift($week, $number, $dayDate, $start, $finish);
				}
				if (!empty($_POST['form'][$a]['monStart'] && $_POST['form'][$a]['monFinish'])){
					$dayDate = date('Y-m-d',strtotime($weekEnding .' -5 day'));
					$start = $_POST['form'][$a]['monStart'];
					$finish = $_POST['form'][$a]['monFinish'];
					print_r ($week);
					print_r ($number);
					print_r ($dayDate);
					print_r ($start);
					print_r ($finish);
					insertShift($week, $number, $dayDate, $start, $finish);
				}
				if (!empty($_POST['form'][$a]['tueStart'] && $_POST['form'][$a]['tueFinish'])){
					$dayDate = date('Y-m-d',strtotime($weekEnding .' -4 day'));
					$start = $_POST['form'][$a]['tueStart'];
					$finish = $_POST['form'][$a]['tueFinish'];
					print_r ($week);
					print_r ($number);
					print_r ($dayDate);
					print_r ($start);
					print_r ($finish);
					insertShift($week, $number, $dayDate, $start, $finish);
				}
				if (!empty($_POST['form'][$a]['wedStart'] && $_POST['form'][$a]['wedFinish'])){
					$dayDate = date('Y-m-d',strtotime($weekEnding .' -3 day'));
					$start = $_POST['form'][$a]['wedStart'];
					$finish = $_POST['form'][$a]['wedFinish'];
					print_r ($week);
					print_r ($number);
					print_r ($dayDate);
					print_r ($start);
					print_r ($finish);
					insertShift($week, $number, $dayDate, $start, $finish);
				}
				if (!empty($_POST['form'][$a]['thuStart'] && $_POST['form'][$a]['thuFinish'])){
					$dayDate = date('Y-m-d',strtotime($weekEnding .' -2 day'));
					$start = $_POST['form'][$a]['thuStart'];
					$finish = $_POST['form'][$a]['thuFinish'];
					print_r ($week);
					print_r ($number);
					print_r ($dayDate);
					print_r ($start);
					print_r ($finish);
					insertShift($week, $number, $dayDate, $start, $finish);
				}
				if (!empty($_POST['form'][$a]['friStart'] && $_POST['form'][$a]['friFinish'])){
					$dayDate = date('Y-m-d',strtotime($weekEnding .' -1 day'));
					$start = $_POST['form'][$a]['friStart'];
					$finish = $_POST['form'][$a]['friFinish'];
					print_r ($week);
					print_r ($number);
					print_r ($dayDate);
					print_r ($start);
					print_r ($finish);
					insertShift($week, $number, $dayDate, $start, $finish);
				}
				if (!empty($_POST['form'][$a]['satStart'] && $_POST['form'][$a]['satFinish'])){
					$dayDate = $weekEnding;
					$start = $_POST['form'][$a]['satStart'];
					$finish = $_POST['form'][$a]['satFinish'];
					print_r ($week);
					print_r ($number);
					print_r ($dayDate);
					print_r ($start);
					print_r ($finish);
					insertShift($week, $number, $dayDate, $start, $finish);
				}
			}
			
		}
	
		
	$conn->close();	
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
		
		$query = "SELECT employee.employee_id,employee.lastName,employee.firstName FROM employee \n"
		. "WHERE employee.section='$section'";
		
		$fresult = $conn->query($query);
		if (!$fresult) die ("Database access failed: " . $conn->error);

		$totalRows = $fresult->num_rows;
		
		echo $totalRows;
		
		$totalRecords = array();
		
		if ($totalRows > 0) {
		
			for ($f = 0 ; $f < $totalRows ; ++$f) {
				$fresult->data_seek($f);
				$frow = $fresult->fetch_array(MYSQLI_ASSOC);
				$fnumber = $frow['employee_id'];
				$ffname = $frow['firstName'];
				$fsname = $frow['lastName'];
				$ffullname = $ffname . ' ' . $fsname;
				$totalRecords[] = array($fnumber, $ffullname);
			}
		}
		//print_r($totalRecords);
		
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

		if ($rows > 0 || $totalRows > 0) {
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
				</tr>';

		$sorted = array();
		$checkName = array();
		
		$big = 0;
		
	for ($j = 0 ; $j < $rows ; ++$j) {
		
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$number = $row['employee_id'];
		
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
			$sorted[14] = '';	
			
			
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
		
			$number = $row['employee_id'];
			$sorted[15] = $number;
			
			echo '<form name="createForm" id="createForm" action="" method="post">';
	
			echo
			'<tr>
				<th>'; echo '<input class="name" id="name" name="name" readonly disabled type="text" value="' . $sorted[14]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="sunStart" name="form['; echo $big; echo '][sunStart]" type="number" step="0.05" value="' . $sorted[0]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="sunFinish" name="form['; echo $big; echo '][sunFinish]" type="number" step="0.05"value="' . $sorted[1]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="monStart" name="form['; echo $big; echo '][monStart]" type="number" step="0.05"value="' . $sorted[2]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="monFinish" name="form['; echo $big; echo '][monFinish]" type="number" step="0.05"value="' . $sorted[3]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="tueStart" name="form['; echo $big; echo '][tueStart]" type="number" step="0.05"value="' . $sorted[4]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="tueFinish" name="form['; echo $big; echo '][tueFinish]" type="number" step="0.05"value="' . $sorted[5]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="wedStart" name="form['; echo $big; echo '][wedStart]" type="number" step="0.05"value="' . $sorted[6]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="wedFinish" name="form['; echo $big; echo '][wedFinish]" type="number" step="0.05"value="' . $sorted[7]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="thuStart" name="form['; echo $big; echo '][thuStart]" type="number" step="0.05"value="' . $sorted[8]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="thuFinish" name="form['; echo $big; echo '][thuFinish]" type="number" step="0.05"value="' . $sorted[9]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="friStart" name="form['; echo $big; echo '][friStart]" type="number" step="0.05"value="' . $sorted[10]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="friFinish" name="form['; echo $big; echo '][friFinish]" type="number" step="0.05"value="' . $sorted[11]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="satStart" name="form['; echo $big; echo '][satStart]" type="number" step="0.05"value="' . $sorted[12]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="satFinish" name="form['; echo $big; echo '][satFinish]" type="number" step="0.05"value="' . $sorted[13]; echo '"/></th>';
				echo '<input class="number" id="number" name="form['; echo $big; echo '][number]" readonly type="hidden" value="' . $sorted[15]; echo '"/>
				';  echo '	
			</tr>';
			$big++;
			array_push($checkName, $number);
		}
		
	}
	
			echo '<div id="rotaLabel" >';
			echo '<p><span>Do rotas for week ending:</span></p>';
			echo '</div>';
			echo '<div id="dateChoice" >';
			echo '<input id="week_ending" class="week_ending" type="text" name="week_ending" maxlength="10" required="true" placeholder="yyyy-mm-dd"  />';
			echo '</div>';
	
	
	$amount = count($totalRecords);
	
	for ($z = 0; $z < $amount; ++$z){
		$newNumber = $totalRecords[$z]['0'];
		$newName = $totalRecords[$z]['1'];
		
		if (!in_array($newNumber, $checkName)) {
			
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
			$sorted[14] = '';	
			
			echo
			'<tr>
				<th>'; echo '<input class="name" id="name" name="name" readonly disabled type="text" value="' . $newName; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="sunStart" name="form['; echo $big; echo '][sunStart]" type="number" step="0.05" value="' . $sorted[0]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="sunFinish" name="form['; echo $big; echo '][sunFinish]" type="number" step="0.05"value="' . $sorted[1]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="monStart" name="form['; echo $big; echo '][monStart]" type="number" step="0.05"value="' . $sorted[2]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="monFinish" name="form['; echo $big; echo '][monFinish]" type="number" step="0.05"value="' . $sorted[3]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="tueStart" name="form['; echo $big; echo '][tueStart]" type="number" step="0.05"value="' . $sorted[4]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="tueFinish" name="form['; echo $big; echo '][tueFinish]" type="number" step="0.05"value="' . $sorted[5]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="wedStart" name="form['; echo $big; echo '][wedStart]" type="number" step="0.05"value="' . $sorted[6]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="wedFinish" name="form['; echo $big; echo '][wedFinish]" type="number" step="0.05"value="' . $sorted[7]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="thuStart" name="form['; echo $big; echo '][thuStart]" type="number" step="0.05"value="' . $sorted[8]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="thuFinish" name="form['; echo $big; echo '][thuFinish]" type="number" step="0.05"value="' . $sorted[9]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="friStart" name="form['; echo $big; echo '][friStart]" type="number" step="0.05"value="' . $sorted[10]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="friFinish" name="form['; echo $big; echo '][friFinish]" type="number" step="0.05"value="' . $sorted[11]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="satStart" name="form['; echo $big; echo '][satStart]" type="number" step="0.05"value="' . $sorted[12]; echo '"/></th>
				<td>'; echo '<input class="timepicker" id="satFinish" name="form['; echo $big; echo '][satFinish]" type="number" step="0.05"value="' . $sorted[13]; echo '"/></th>';
				echo '<input class="number" id="number" name="form['; echo $big; echo '][number]" readonly type="hidden" value="' . $newNumber; echo '"/>
				';  echo '	
			</tr>';
			$big++;
		}
	}
	
			echo "big number: " . $big;
			$_SESSION['rows'] = $big;
			
			echo '<tr><th colspan="15" >'; echo '<input class="timeSubmit" type="submit" name="submit" value="Save Changes"/>'; echo '</th></tr>';
			echo '</form>';	
			echo '</table>';
			
			
			echo '<div id="weekLabel">';
			echo '<p>Week Ending: </p>'; 
			echo '<div id="week">';
			echo $week_ending;
			echo '</div>';
			echo '</div>';
			
			$_SESSION['weekEnding'] = $week_ending;
			$_SESSION['sectionChoose'] = $section;
			$_SESSION['startEdit'] = false;
			
		}
		else {
			
				if (!$_SESSION['executedEdit'] && !$_SESSION['startEdit']){
					$weekEnding = $_SESSION['weekEnding'];
					$section = $_SESSION['sectionChoose']; 
					$_SESSION['executedEdit'] = true;
					getAllRotas($weekEnding, $section);
				}
				else if (!$_SESSION['startEdit']) {
					$weekEnding = $_SESSION['weekEnding'];
					$section= $_SESSION['sectionChoose'];
					$_SESSION['executedEdit'] = false;
					getAllRotas($weekEnding, $section);	
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
		
		
		if (!isset($_POST['selectWeek'])) {
			$weekEnding = date('Y-m-d',strtotime('next saturday'));
		}
		else {
			$weekEnding = mysql_entities_fix_string($conn, $_POST['selectWeek']);
		}
		if (!isset($_POST['section'])) {
			$section = $_SESSION['sectionChoose'];
		}
		else {
			$section = mysql_entities_fix_string($conn, $_POST['section']);
		}
		
		getAllRotas($weekEnding, $section);   
		}

	function checkForExisting($weekEnding, $number){

		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
			
			
			$week_beginning = date('Y-m-d', strtotime('-6 day', strtotime($weekEnding)));
		
			$query = "SELECT firstname,lastname,start_shift,end_shift,day FROM employee,schedule,date \n"
			. "WHERE schedule.employee_id=employee.employee_id AND schedule.Week_ending='$weekEnding'\n"
			. "AND date.fulldate=schedule.fulldate\n"
			. "AND date.fulldate BETWEEN '$week_beginning' AND '$weekEnding'\n"
			. "AND schedule.employee_id='$number'";

			$result = $conn->query($query);
			if (!$result) die ("Database access failed: " . $conn->error);
			
			$checkRows = $result->num_rows;
		
			if ($checkRows > 0) {
	
			$delete = "DELETE FROM schedule WHERE schedule.employee_id = $number \n"
					. "AND schedule.Week_ending = '$weekEnding'";
	
				if ($conn->query($delete) === TRUE) {
					echo "records deleted successfully";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
			
			$conn->close();	
		}

		function insertShift($week, $number, $dayDate, $start, $finish){
			$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if ($conn->connect_error) die($conn->connect_error);
			
			$sql = "INSERT INTO schedule (shift_id, Week_ending, employee_id, fulldate, start_shift, end_shift) \n"
				. "VALUES (NULL, '$week', $number, '$dayDate', $start, $finish)";
				
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
		

			
		<form name="rotaForm" id="rotaForm" action="" onsubmit="getWeek()" method="post"> 
		
			<div id="sectionLabel" >
				<p><span>View all rotas for which section:</span></p> 
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
			
			<input id="selectWeek" class="selectWeek" type="hidden" name="selectWeek" maxlength="10" value="" required="true" placeholder="yyyy-mm-dd"  />
			
			<input id="submitButton" type="submit" name="sectionSubmit" value="sectionSubmit" />
		</form>
	  	

      
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
	<script src="edit.js"></script>
  </body>
</html>
