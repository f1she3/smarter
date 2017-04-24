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
	function rm_account($username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'DELETE FROM users WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		$query = mysqli_prepare($mysqli, 'DELETE FROM friends WHERE (BINARY sender = ? OR BINARY contact = ?)');
		mysqli_stmt_bind_param($query, 'ss', $username, $username);
		mysqli_stmt_execute($query);
		// We don't want our friends to receive message notifications, they won't be abble to see the messages (friend doesn't exist error)
		$query = mysqli_prepare($mysqli, 'UPDATE private SET viewed = 1 WHERE BINARY sender = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
	}

