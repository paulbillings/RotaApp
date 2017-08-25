<?php
	session_start();
	if (!$_SESSION['logged_in']){
		header("Location: login_page.php");
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>GotoRota - Admin Menu</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="adminMenuStyle.css" type="text/css" rel="stylesheet"/>
	<link href="resources/jquery-ui.css" rel="stylesheet">
  </head>
  <body>
    <div class="rota" >

		<div id="header" >
			<p><span id="goto" >Goto</span><span id="rota" >Rota</span></p>
		</div>
		
		<nav>
			<ul>
				<li><a href="userProfile.php">Home</a></li>
				<li><a href="" class="current">Admin</a></li>
				<li><a href="">Help</a></li>
				<li><a href="login_page.php">Logout</a></li>
			</ul>
		</nav>
		
		<ul id="rotaMenu">
			<li class="header">Rotas</li>
				<li><a href="admin.php">View rotas by section</a></li>
				<li><a href="createRota.php">Create rota</a></li>
				<li><a href="editRota.php">Edit rota</a></li>
				<li><a href="">Delete rota</a></li>
		</ul>
		
		<ul id="colleagueMenu">
			<li class="header">Colleagues</li>
				<li>View colleague details</li>
				<li>Add colleague</li>
				<li>Edit colleague</li>
				<li>Delete colleague</li>
		</ul>
      
    </div>
	<script src="resources/jquery-3.2.1.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="index.js"></script>
  </body>
</html>
