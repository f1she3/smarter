<?php

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
		$query = mysqli_prepare($mysqli, 'SELECT contact, reason FROM blocked WHERE BINARY sender = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $contact, $reason);
		$i = 0;
		echo "<h2 class=\"text-center page-header\">Utilisateurs bloqués<h2>
			<div class=\"col-md-10 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1 user-list text-center\">";
		while($result = mysqli_stmt_fetch($query)){
			echo "<a class=\"btn btn-warning\" href=\"".constant('BASE_URL')."block&user=".$contact."\">".$contact."</a>
				<h6 class=\"text-center\">Raison : \"".$reason."\"</h6>";
			$i++;
		}
		if($i === 0){
			echo "<p class=\"text-center\">Vous n'avez bloqué aucun membre</p>";
			set_error(false, 'search', false, 'contacts');
		}
		echo "</div>";
	}
