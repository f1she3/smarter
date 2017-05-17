<?php

	function demute($username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'DELETE FROM mute WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
	}
	function deban($username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'DELETE FROM ban WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
	}
	function is_mute($username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT end FROM mute WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $end);
		$result = mysqli_stmt_fetch($query);
		if(empty($result)){
			return false;	
		
		}else{
			$now = date('Y-m-d H:i:s');
			if($now > $end){
				demute($username);

				return false;
			
			}else{
				return true;
			}
		}
	}
	function is_banned($username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT msg FROM ban WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $reason);
		$result = mysqli_stmt_fetch($query);
		if(empty($result)){
			return false;
		
		}else{
			$result = array();
			$result['message'] = $reason;
			return $result;	
		}
	}
	function is_blocked($current_user, $username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT reason FROM blocked WHERE BINARY sender = ? AND BINARY contact = ?');
		mysqli_stmt_bind_param($query, 'ss', $current_user, $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $reason);
		$result = mysqli_stmt_fetch($query);
		if(empty($result)){
			return false;
		
		}else{
			$result = $reason;
			return $result;
		}
	}
	function set_mute($username, $min, $hour, $day){
		$end = date_create();
		if($min){
			date_modify($end, '+'.$min.' min');
		}
		if($hour){
			date_modify($end, '+'.$hour.' hour');
		}
		if($day){
			date_modify($end, '+'.$day.' day');
		}
		$end = date_format($end, 'Y-m-d G:i:s');
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT id FROM mute WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		$i = 0;
		while($result = mysqli_stmt_fetch($query)){
			if($result != 0){
				$i++;	
			}
		}
		if($i == 0){
			$mysqli = get_link();
			$query = mysqli_prepare($mysqli, 'INSERT INTO mute (name, start, end) VALUES (?, NOW(), ?)');
			mysqli_stmt_bind_param($query, 'ss', $username, $end);
			mysqli_stmt_execute($query);
		
		}else{
			$mysqli = get_link();
			$query = mysqli_prepare($mysqli, 'UPDATE mute SET start = NOW(), end = ? WHERE BINARY name = ?');
			mysqli_stmt_bind_param($query, 'ss', $end, $username);
			mysqli_stmt_execute($query);
		}
	}
	function set_rank($username, $rank){
		$ranks = get_rank_list();
		if(isset($ranks[$rank])){
			$mysqli = get_link();
			$query = mysqli_prepare($mysqli, 'UPDATE users SET rank = ? WHERE BINARY name = ?');
			mysqli_stmt_bind_param($query, 'is', $rank, $username);
			mysqli_stmt_execute($query);
		
		}else{
			return false;
		}
	}
	function set_ban($username, $reason){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'INSERT INTO ban (name, msg) VALUES (?, ?)');
		mysqli_stmt_bind_param($query, 'ss', $username, $reason);
		mysqli_stmt_execute($query);
	}
	function check_rank($username, $rank){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT id FROM users WHERE BINARY name = ? AND rank = ?');
		mysqli_stmt_bind_param($query, 'ss', $username, $rank);
		mysqli_stmt_execute($query);
		$result = mysqli_stmt_fetch($query);
		if($result == 0){
			return false;
		
		}else{
			return true;
		}
	}
	function get_rank($username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT rank FROM users WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $rank);
		mysqli_stmt_fetch($query);
		
		return $rank;
	}
	function get_rank_list(){
		// Add ranks here
		$ranks[0] = 'Utilisateur';
		$ranks[1] = 'Modérateur';
		$ranks[2] = 'Chef modérateur';
		$ranks[3] = 'Administrateur';
		$ranks[4] = 'Chef Administrateur';
		// Change this value to the number of ranks
		$ranks['max'] = 4;

		return $ranks;
	}
