<!DOCTYPE html>
<html>
  <head>
    <title>GotoRota - Create Rotas</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="createRotaStyle.css" type="text/css" rel="stylesheet"/>
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
		$_SESSION['sectionChoose'] = "Checkouts";
	}


	define('DB_NAME', 'rotas');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) die($conn->connect_error);
	
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['submit'])) {
		$weekEnding = mysql_entities_fix_string($conn, $_POST['week_ending']);
		//$colNumber = mysql_entities_fix_string($conn, $_SESSION['pass']);
		//$section = mysql_entities_fix_string($conn, $_POST['section']);
		$_SESSION['sectionChoose'] = $section;
		
		$SelectedElem = array();
		$SelectedElem = implode(",", array_keys($_POST['submit']));
		print_r ($_POST['form'][$SelectedElem]);
	}
}

function mysql_entities_fix_string($conn, $string) {
	return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string) {
	if (get_magic_quotes_gpc()) $string = stripcslashes($string);
	return $conn->real_escape_string($string);
}

function getAllRotas($section) {

	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) die($conn->connect_error);
		
		//$week_ending = mysql_entities_fix_string($conn, $_POST['week_ending']);
		//$week_ending = $weekEnding;
		
		//$week_beginning = date('Y-m-d', strtotime('-6 day', strtotime($week_ending)));
		
		//$number = $colNumber;
		
		
	$query = "SELECT employee.employee_id,employee.lastName,employee.firstName FROM employee \n"
		. "WHERE employee.section='$section'";
		

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
		
		

	for ($j = 0 ; $j < $rows ; ++$j) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		

			
			
			$fname = $row['firstName'];
			$sname = $row['lastName'];
			$fullname = $fname . ' ' . $sname;
			$sorted[14] = $fullname;
			
	echo '<form name="nameForm" id="nameForm" action="" method="post">';		
			echo
			'<tr>
				<th>'; echo '<input class="name" id="sunStart" name="form[$j][name]" readonly type="text" value="' . $sorted[14]; echo '"/></th>
				<td>'; echo '<input class="time" id="sunStart" name="form[$j][sunStart]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="sunFinish" name="form[$j][sunFinish]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="monStart" name="form[$j][monStart]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="monFinish" name="form[$j][monFinish]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="tueStart" name="form[$j][tueStart]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="tueFinish" name="form[$j][tueFinish]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="wedStart" name="form[$j][wedStart]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="wedFinish" name="form[$j][wedFinish]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="thuStart" name="form[$j][thuStart]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="thuFinish" name="form[$j][thuFinish]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="friStart" name="form[$j][friStart]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="friFinish" name="form[$j][friFinish]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="satStart" name="form[$j][satStart]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="time" id="satFinish" name="form[$j][satFinish]" type="number" value=""/>'; echo '</td>
				<td>'; echo '<input class="timeSubmit" type="submit" name="submit[$j]" value="Save Changes">'; echo '</td>
			</tr>';
	echo '</form>';
			
			
	}
	echo '</table>';
	
		
			
			//$_SESSION['weekEnding'] = $week_ending;
			$_SESSION['section'] = $section;
			$_SESSION['start'] = false;
			
		}
		else {
			
				if (!$_SESSION['executed'] && !$_SESSION['start']){
					//echo '<script language="javascript">';
					//echo 'alert("No Rotas for selected week")';
					//echo '</script>';
					//$weekEnding = $_SESSION['weekEnding'];
					$section= $_SESSION['section'];
					//$colNumber = $_SESSION['pass'];
					getAllRotas($section);
					$_SESSION['executed'] = true;
				}
				else if (!$_SESSION['start']) {
					//$weekEnding = $_SESSION['weekEnding'];
					//$colNumber = $_SESSION['pass'];
					$section= $_SESSION['section'];
					getAllRotas($section);
					$_SESSION['executed'] = false;
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
		
		
		if (!isset($_POST['week_ending'])) {
			$weekEnding = date('Y-m-d',strtotime('next saturday'));
			$section = "Checkouts";
		}
		else {
			$weekEnding = mysql_entities_fix_string($conn, $_POST['week_ending']);
			$section = mysql_entities_fix_string($conn, $_POST['section']);
			$_SESSION['sectionChoose'] = $section;
		}
		
		//echo $weekEnding;
		
		//$colNumber = mysql_entities_fix_string($conn, $_SESSION['pass']);
		getAllRotas($section);   
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
				<p><span>Do rotas for which section:</span></p> 
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
				<p><span>Do rotas for week ending:</span></p> 
			</div>
			<div id="dateChoice" >
				<input id="week_ending" type="text" name="week_ending" maxlength="10" required="true" placeholder="yyyy-mm-dd"  />
			</div>
			<input id="submitButton" type="submit" value="Submit" />
		</form>
	  	

      
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="index.js"></script>
  </body>
</html>
