<?php

	require_once '../functions/init.php';
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		if(isset($_POST['name']) && !empty($_POST['name']) && strlen($_POST['name']) <= 15){
			$_POST['name'] = secure($_POST['name']);
			$query = mysqli_prepare($mysqli, 'SELECT name FROM users WHERE BINARY name = ?');
			mysqli_stmt_bind_param($query, 's', $_POST['name']);
			mysqli_stmt_execute($query);
			mysqli_stmt_bind_result($query, $name);
			mysqli_stmt_fetch($query);
			echo $name;
		
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
