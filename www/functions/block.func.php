<?php

	function block($current_user, $username, $reason){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'INSERT INTO rekt (sender, contact, reason) VALUES (?, ?, ?)');
		mysqli_stmt_bind_param($query, 'sss', $current_user, $username, $reason);
		mysqli_stmt_execute($query);
	}
