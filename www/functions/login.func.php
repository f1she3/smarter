<?php

// Returns an username if the input is an email address
function find_username($input){
	$mysqli = get_link();
	$email = sha1($input);
	$query = mysqli_prepare($mysqli, 'SELECT username FROM users WHERE BINARY username = ? OR email = ?');
	mysqli_stmt_bind_param($query, 'ss', $input, $email);
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $db_username);
	mysqli_stmt_fetch($query);
	
	return $db_username;
}
function check_ids($type, $input, $username){
	$mysqli = get_link();
	if($type == 'username'){
		$query = mysqli_prepare($mysqli, 'SELECT id FROM users WHERE BINARY username = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		$result = mysqli_stmt_fetch($query);
		if(!empty($result)){
			return true;
		
		}else{
			return false;
		}
	
	}else if($type == 'password'){
		$query = mysqli_prepare($mysqli, 'SELECT password FROM users WHERE BINARY username = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $db_password);
		mysqli_stmt_fetch($query);
		if(password_verify($input, $db_password)){
			return true;
	
		}else{
			return false;
		}
	}
}
