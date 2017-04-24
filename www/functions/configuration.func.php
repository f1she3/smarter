<?php

	function check_pass($input_password, $username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT password FROM users WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $db_password);
		mysqli_stmt_fetch($query);
		if(password_verify($input_password, $db_password)){
			return true;
	
		}else{
			return false;
		}
	}
	function update_pass($name, $new_password){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'UPDATE users SET password = ? WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 'ss', $new_password, $name);
		mysqli_stmt_execute($query);
	}
