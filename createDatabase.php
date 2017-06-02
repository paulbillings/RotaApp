<?php 

define('DB_NAME', 'rotas');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) die($conn->connect_error);

echo 'Connected successfully!';


$date = "CREATE TABLE date (
    date_id int NOT NULL AUTO_INCREMENT,
    fulldate date NOT NULL,
    year int NOT NULL,
    month varchar(10) NOT NULL,
    day varchar(10) NOT NULL,
	PRIMARY KEY (date_id)
)";
//CONSTRAINT Date_pk PRIMARY KEY (Date_id)

$result = $conn->query($date);
if (!$result) die ("Database access failed: " . $conn->error);

$employee = "CREATE TABLE employee (
    employee_id int NOT NULL AUTO_INCREMENT,
    firstName varchar(30) NOT NULL,
    lastName varchar(30) NOT NULL,
    section varchar(20) NOT NULL,
    contractHours int NOT NULL,
	PRIMARY KEY (employee_id) 
)";

$result = $conn->query($employee);
if (!$result) die ("Database access failed: " . $conn->error);

//CONSTRAINT Employee_pk PRIMARY KEY (Employee_id)

$schedule = "CREATE TABLE schedule (
    shift_id int NOT NULL AUTO_INCREMENT,
    employee_id int NOT NULL,
    date_id int NOT NULL,
    start_shift decimal(5,3) NOT NULL,
    end_shift decimal(5,3) NOT NULL,
	PRIMARY KEY (shift_id)
)";
//CONSTRAINT Schedule_pk PRIMARY KEY (Shift_id)

/*foreign keys
-- Reference: Schedule_Date (table: Schedule)
--ALTER TABLE Schedule ADD CONSTRAINT Schedule_Date FOREIGN KEY Schedule_Date (Date_id)
  --  REFERENCES Date (Date_id);

-- Reference: Schedule_Employee (table: Schedule)
--ALTER TABLE Schedule ADD CONSTRAINT Schedule_Employee FOREIGN KEY Schedule_Employee (Employee_id)
  --  REFERENCES Employee (Employee_id);

-- End of file.////*/

$result = $conn->query($schedule);
if (!$result) die ("Database access failed: " . $conn->error);


echo 'Connected created tables!';
?>
