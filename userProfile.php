<?php
	session_start();
	if (!$_SESSION['logged_in']){
		header("Location: login_page.php");
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>GotoRota</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="style.css" type="text/css" rel="stylesheet"/>
	<link href="resources/jquery-ui.css" rel="stylesheet">
  </head>
  <body>
    <div class="rota" >

		<div id="header" >
			<p><span id="goto" >Goto</span><span id="rota" >Rota</span></p>
		</div>
		
		<nav>
			<ul>
				<li><a href="" class="current">Home</a></li>
				<li><a href="">Admin</a></li>
				<li><a href="">Help</a></li>
				<li><a href="login_page.php">Logout</a></li>
			</ul>
		</nav>
		

			
		<form name="rotaForm" id="rotaForm" action="" method="post"> 
			
			<div id="rotaLabel" >
				<p><span>View your rota for week ending:</span></p> 
			</div>
			<div id="dateChoice" >
				<input id="week_ending" type="text" name="week_ending" maxlength="10" required="true" placeholder="yyyy-mm-dd"  />
			</div>
			<input id="submitButton" type="submit" value="Submit" />
		</form>
	  	
<?php

	define('DB_NAME', 'rotas');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) die($conn->connect_error);
	
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$weekEnding = mysql_entities_fix_string($conn, $_POST['week_ending']);
	$colNumber = mysql_entities_fix_string($conn, $_SESSION['pass']);
	getRota($colNumber, $weekEnding);
}

function mysql_entities_fix_string($conn, $string) {
	return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string) {
	if (get_magic_quotes_gpc()) $string = stripcslashes($string);
	return $conn->real_escape_string($string);
}


function getRota($colNumber, $weekEnding) {

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) die($conn->connect_error);
		
		//$week_ending = mysql_entities_fix_string($conn, $_POST['week_ending']);
		$week_ending = $weekEnding;
		
		$week_beginning = date('Y-m-d', strtotime('-6 day', strtotime($week_ending)));
		
		$number = $colNumber;

	$query = "SELECT firstname,lastname,start_shift,end_shift,day FROM employee,schedule,date \n"
		. "WHERE schedule.employee_id=employee.employee_id AND schedule.Week_ending='$week_ending'\n"
		. "AND date.fulldate=schedule.fulldate\n"
		. "AND date.fulldate BETWEEN '$week_beginning' AND '$week_ending'\n"
		. "AND schedule.employee_id='$number'";

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

	for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
	
		if ($row['day'] === 'Sunday') {
			$sorted[0] = $row['start_shift'];
			$sorted[1] = $row['end_shift'];
		}
		if ($row['day'] === 'Monday') {
			$sorted[2] = $row['start_shift'];
			$sorted[3] = $row['end_shift'];
		}
		if ($row['day'] === 'Tuesday') {
			$sorted[4] = $row['start_shift'];
			$sorted[5] = $row['end_shift'];
		}
		if ($row['day'] === 'Wednesday') {
			$sorted[6] = $row['start_shift'];
			$sorted[7] = $row['end_shift'];
		}
		if ($row['day'] === 'Thursday') {
			$sorted[8] = $row['start_shift'];
			$sorted[9] = $row['end_shift'];
		}
		if ($row['day'] === 'Friday') {
			$sorted[10] = $row['start_shift'];
			$sorted[11] = $row['end_shift'];
		}
		if ($row['day'] === 'Saturday') {
			$sorted[12] = $row['start_shift'];
			$sorted[13] = $row['end_shift'];
		}
	
		$fname = $row['firstname'];
		$sname = $row['lastname'];
		$fullname = $fname . ' ' . $sname;
		$sorted[14] = $fullname;
		
		
	}
	//<th>'; echo $sorted[14]; echo '</th>
	echo
		'<tr>
			<th>'; echo '<p>Week ending </p>'; echo $week_ending; echo '</th>
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
		</tr>';


	echo '</table>';
	
	echo '<div id="welcome">';
	echo '<p>Welcome</p>'; 
	echo '<div id="userName">';
	echo $fullname;
	echo '</div>';
	echo '</div>';
			
			$_SESSION['weekEnding'] = $week_ending;
		}
		else {
			
				if (!$_SESSION['executed']){
					echo '<script language="javascript">';
					echo 'alert("No Rota for selected week")';
					echo '</script>';
					$weekEnding = $_SESSION['weekEnding'];
					$colNumber = $_SESSION['pass'];
					getRota($colNumber, $weekEnding);
					$_SESSION['executed'] = true;
				}
				else {
					$weekEnding = $_SESSION['weekEnding'];
					$colNumber = $_SESSION['pass'];
					getRota($colNumber, $weekEnding);
					$_SESSION['executed'] = false;
				}		
		}		
		
	$result->close();
	$conn->close();	
	
}


	
		if (isset($_SESSION['pass'])) {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($conn->connect_error) die($conn->connect_error);
		
		
		if (!isset($_POST['week_ending'])) {
			$weekEnding = date('Y-m-d',strtotime('next saturday'));
		}
		else {
			$weekEnding = mysql_entities_fix_string($conn, $_POST['week_ending']);
		}
		
		//echo $weekEnding;
		
		$colNumber = mysql_entities_fix_string($conn, $_SESSION['pass']);
		getRota($colNumber, $weekEnding);   
		}



	

?>
      
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="index.js"></script>
  </body>
</html>
