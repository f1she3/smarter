<?php

	// Checks if the id passed in parameter is already in the db, so if it's already taken or not
	function is_used($field, $id){
		$mysqli = get_link();
		if($field == 'email'){
			$query = mysqli_prepare($mysqli, 'SELECT id FROM users WHERE email = ?');
			mysqli_stmt_bind_param($query, 's', $id);
			mysqli_stmt_execute($query);
			$result = mysqli_stmt_fetch($query);
			if($result == 0){
				return false;
			
			}else{
				return true;
			}
		
		}else if($field == 'name'){
			$query = mysqli_prepare($mysqli, 'SELECT id FROM users WHERE BINARY name = ?');
			mysqli_stmt_bind_param($query, 's', $id);
			mysqli_stmt_execute($query);
			$result = mysqli_stmt_fetch($query);
			if($result == 0){
				return false;
		
			}else{
				return true;
			}
		}
	}
	function check_pattern($pattern, $str){
		if(preg_match($pattern, $str)){
			return true;
		
		}else{
			return false;
		}
	}
	function register($name, $email, $password){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'INSERT INTO users (name, email, password, reg_date) VALUES (?, ?, ?, NOW())');
		mysqli_stmt_bind_param($query, 'sss', $name, $email, $password);
		mysqli_stmt_execute($query);
	}
