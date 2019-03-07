<?php

require_once '../functions/init.php';
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	if(isset($_POST['username']) && !empty($_POST['username'])){
		$_POST['username'] = secure($_POST['username']);
		$query = mysqli_prepare($mysqli, 'SELECT username FROM users WHERE BINARY username = ?');
		mysqli_stmt_bind_param($query, 's', $_POST['username']);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $username);
		mysqli_stmt_fetch($query);
		echo $username;
	}else{
		if(isset($_POST['email']) && !empty($_POST['email']) && strlen($_POST['email']) <= 40){
			$_POST['email'] = secure($_POST['email']);
			$email = sha1(strtolower($_POST['email']));
			$query = mysqli_prepare($mysqli, 'SELECT email FROM users WHERE email = ?');
			mysqli_stmt_bind_param($query, 's', $email);
			mysqli_stmt_execute($query);
			mysqli_stmt_bind_result($query, $email);
			mysqli_stmt_fetch($query);
			echo $email;
		}
	}
}else{
	redirect(2);
}
