<?php

	function block($current_user, $username, $reason){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'INSERT INTO blocked (sender, contact, reason) VALUES (?, ?, ?)');
		mysqli_stmt_bind_param($query, 'sss', $current_user, $username, $reason);
		mysqli_stmt_execute($query);
	}
	function block($username, $contact, $reason){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'INSERT INTO blocked (sender, contact, reason) VALUES (?, ?, ?)');
		mysqli_stmt_bind_param($query, 'sss', $username, $contact, $reason);
		mysqli_stmt_execute($query);
	}
	function unblock($username, $contact){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'DELETE FROM blocked WHERE BINARY sender = ? and contact = ?');
		mysqli_stmt_bind_param($query, 'ss', $username, $contact);
		mysqli_stmt_execute($query);
	}
	function display_blocked_users($username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT contact FROM blocked WHERE BINARY sender = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $contact);
		$i = 0;
		echo "<h2 class=\"text-center page-header\">Membres bloqués<h2>";
		while($result = mysqli_stmt_fetch($query)){
			echo "<a class=\"btn btn-warning\" href=\"".constant('BASE_URL')."block&user=".$contact."\">".$contact."</a>";
		}
		if($i === 0){
			echo "<p class=\"text-center\">Vous n'avez bloqué aucun membre</p>";
		}
	}
