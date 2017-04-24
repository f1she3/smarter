<?php

	function rm_notifications($sender){
		$mysqli = get_link();
		$one = 1;
		$query = mysqli_prepare($mysqli, 'DELETE FROM private WHERE sender = ? AND is_typing = ?');
		mysqli_stmt_bind_param($query, 'si', $sender, $one);
		mysqli_stmt_execute($query);
	}
