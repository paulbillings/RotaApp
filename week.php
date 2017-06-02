<?php
define('DB_NAME', 'rotas');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) die($conn->connect_error);
echo 'Connected successfully!'. '<br><br>';

$week_ending = $_POST['week_ending'];
$week_beginning = date('Y-m-d', strtotime('-6 day', strtotime($week_ending)));
echo $week_beginning . '<br>';

$query = "SELECT lastname,start_shift,end_shift,day FROM employee,schedule,date \n"
    . "WHERE schedule.employee_id=employee.employee_id AND schedule.Week_ending='$week_ending'\n"
    . "AND date.fulldate=schedule.fulldate\n"
    . "AND date.fulldate BETWEEN '$week_beginning' AND '$week_ending'";

$result = $conn->query($query);
if (!$result) die ("Database access failed: " . $conn->error);

$rows = $result->num_rows;

for ($j = 0 ; $j < $rows ; ++$j)
{
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	
	echo 'Lastname: ' . $row['lastname'] . '<br>';
	echo 'Start of shift: ' . $row['start_shift'] . '<br>';
	echo 'End of shift: ' . $row['end_shift'] . '<br>';
	echo 'day of shift: ' . $row['day'] . '<br><br>';
}
	

$result->close();
$conn->close();
?>