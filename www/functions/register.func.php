<?php

// Checks if the id passed in parameter is already in the db, so if it's already taken or not
function is_used($field, $id){
	$mysqli = get_link();
	$query = mysqli_prepare($mysqli, 'SELECT id FROM users WHERE BINARY username = ?');
	mysqli_stmt_bind_param($query, 's', $id);
	mysqli_stmt_execute($query);
	$result = mysqli_stmt_fetch($query);
	if($result == 0){
		return false;
	}else{
		return true;
	}
}
function check_pattern($pattern, $str){
	if(preg_match($pattern, $str)){
		return true;
	}else{
		return false;
	}
}
function register($username, $email, $password){
	$mysqli = get_link();
	$query = mysqli_prepare($mysqli, 'INSERT INTO users (username, email, password, regDate) VALUES (?, ?, ?, NOW())');
	mysqli_stmt_bind_param($query, 'sss', $username, $email, $password);
	mysqli_stmt_execute($query);
	$query = mysqli_prepare($mysqli, 'SELECT id FROM users WHERE BINARY username = ?');
	mysqli_stmt_bind_param($query, 's', $username);
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $id);
	mysqli_stmt_fetch($query);
	if($id == 1){
		$ranks = get_rank_list();
		set_rank($username, $ranks['max']);
	}
}
